<?php

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
});

function makePlan(): Plan
{
    return Plan::create(['name' => 'Mensuel', 'price' => 5000, 'duration_days' => 30]);
}

test('un apprenant peut s\'inscrire et se connecter', function () {
    $this->postJson('/api/auth/register', [
        'name' => 'Awa',
        'email' => 'awa@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])->assertCreated()->assertJsonPath('user.role', 'apprenant')->assertJsonStructure(['token']);

    $this->postJson('/api/auth/login', ['email' => 'awa@example.com', 'password' => 'secret-password'])
        ->assertOk()->assertJsonStructure(['token']);
});

test('seul l\'admin accède à l\'espace administrateur', function () {
    Sanctum::actingAs(User::factory()->create());
    $this->getJson('/api/admin/dashboard')->assertForbidden();

    Sanctum::actingAs(User::factory()->admin()->create());
    $this->getJson('/api/admin/dashboard')->assertOk();
});

test('l\'admin publie une vidéo uploadée', function () {
    Sanctum::actingAs(User::factory()->admin()->create());

    $response = $this->post('/api/admin/videos', [
        'title' => 'Introduction à Laravel',
        'source_type' => 'upload',
        'file' => UploadedFile::fake()->create('intro.mkv', 1024, 'video/x-matroska'),
        'is_published' => '1',
    ], ['Accept' => 'application/json'])->assertCreated();

    $video = Video::first();
    expect($video->slug)->toBe('introduction-a-laravel')
        ->and($video->is_published)->toBeTrue()
        ->and($video->published_at)->not->toBeNull();
    Storage::disk('local')->assertExists($video->file_path);
    $response->assertJsonPath('data.player.type', 'stream');
});

test('l\'admin peut publier une vidéo YouTube', function () {
    Sanctum::actingAs(User::factory()->admin()->create());

    $this->postJson('/api/admin/videos', [
        'title' => 'Excel avancé',
        'source_type' => 'youtube',
        'external_url' => 'https://www.youtube.com/watch?v=abc',
    ])->assertCreated()->assertJsonPath('data.player.url', 'https://www.youtube.com/watch?v=abc');
});

test('un apprenant sans abonnement ne peut pas regarder une vidéo payante', function () {
    $video = Video::create(['title' => 'Cours', 'source_type' => 'upload', 'file_path' => 'videos/a.mp4', 'is_published' => true]);
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->getJson('/api/videos')->assertOk()->assertJsonPath('data.0.can_watch', false);
    $this->getJson("/api/videos/{$video->slug}")->assertOk()
        ->assertJsonPath('data.can_watch', false)
        ->assertJsonMissingPath('data.player')
        ->assertJsonMissingPath('data.file_path');
});

test('une vidéo gratuite est accessible sans abonnement', function () {
    $video = Video::create(['title' => 'Gratuit', 'source_type' => 'youtube', 'external_url' => 'https://youtu.be/x', 'is_published' => true, 'is_free' => true]);
    Sanctum::actingAs(User::factory()->create());

    $this->getJson("/api/videos/{$video->slug}")->assertJsonPath('data.can_watch', true)
        ->assertJsonPath('data.player.url', 'https://youtu.be/x');
});

test('les vidéos non publiées sont invisibles pour les apprenants', function () {
    $video = Video::create(['title' => 'Brouillon', 'source_type' => 'youtube', 'external_url' => 'https://youtu.be/x']);
    Sanctum::actingAs(User::factory()->create());

    $this->getJson('/api/videos')->assertJsonCount(0, 'data');
    $this->getJson("/api/videos/{$video->slug}")->assertNotFound();
});

test('flux complet : demande d\'abonnement, validation admin, lecture du flux', function () {
    Storage::disk('local')->put('videos/a.mp4', 'fake-video-content');
    $video = Video::create(['title' => 'Cours', 'source_type' => 'upload', 'file_path' => 'videos/a.mp4', 'is_published' => true]);
    $plan = makePlan();
    $user = User::factory()->create();

    Sanctum::actingAs($user);
    $subscriptionId = $this->postJson('/api/subscriptions', ['plan_id' => $plan->id, 'payment_method' => 'orange_money', 'payment_reference' => 'TX123'])
        ->assertCreated()
        ->assertJsonPath('subscription.status', 'pending')
        ->json('subscription.id');

    // Pas encore validé : pas d'accès
    $this->getJson("/api/videos/{$video->slug}")->assertJsonPath('data.can_watch', false);

    Sanctum::actingAs(User::factory()->admin()->create());
    $this->postJson("/api/admin/subscriptions/{$subscriptionId}/activate")
        ->assertOk()
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.days_remaining', 30);

    Sanctum::actingAs($user->fresh());
    $streamUrl = $this->getJson("/api/videos/{$video->slug}")
        ->assertJsonPath('data.can_watch', true)
        ->json('data.player.url');

    $this->get($streamUrl)->assertOk();
    // URL non signée refusée
    $this->get("/api/videos/{$video->id}/stream/{$user->id}")->assertForbidden();
});

test('le flux refuse un utilisateur dont l\'abonnement a expiré', function () {
    Storage::disk('local')->put('videos/a.mp4', 'x');
    $video = Video::create(['title' => 'Cours', 'source_type' => 'upload', 'file_path' => 'videos/a.mp4', 'is_published' => true]);
    $user = User::factory()->create();
    $subscription = Subscription::create(['user_id' => $user->id, 'plan_id' => makePlan()->id, 'amount' => 5000]);
    $subscription->activate();

    $url = Illuminate\Support\Facades\URL::temporarySignedRoute('videos.stream', now()->addHour(), ['video' => $video->id, 'user' => $user->id]);
    $this->get($url)->assertOk();

    $this->travel(31)->days();
    $this->artisan('subscriptions:expire')->assertSuccessful();

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Expired);
    $this->get(Illuminate\Support\Facades\URL::temporarySignedRoute('videos.stream', now()->addHour(), ['video' => $video->id, 'user' => $user->id]))
        ->assertForbidden();
});

test('un renouvellement anticipé démarre à la fin de l\'abonnement en cours', function () {
    $user = User::factory()->create();
    $plan = makePlan();

    $first = Subscription::create(['user_id' => $user->id, 'plan_id' => $plan->id, 'amount' => 5000])->activate();
    $second = Subscription::create(['user_id' => $user->id, 'plan_id' => $plan->id, 'amount' => 5000])->activate();

    expect($second->starts_at->equalTo($first->ends_at))->toBeTrue();
});

test('l\'admin gère les formules d\'abonnement', function () {
    Sanctum::actingAs(User::factory()->admin()->create());

    $id = $this->postJson('/api/admin/plans', ['name' => 'Annuel', 'price' => 48000, 'duration_days' => 365])
        ->assertCreated()->assertJsonPath('slug', 'annuel')->json('id');

    $this->putJson("/api/admin/plans/{$id}", ['price' => 40000])->assertOk();
    $this->getJson('/api/plans')->assertJsonPath('0.price', '40000.00');
});
