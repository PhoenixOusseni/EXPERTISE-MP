<?php

namespace App\Http\Controllers\Web\Apprenant;

use App\Enums\LearnerProfile;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Publicite;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile_type instanceof LearnerProfile ? $user->profile_type : null;

        $categories = Category::withCount([
            'videos' => fn ($q) => $q->published()
                ->when($profile, fn ($q) => $q->forProfile($profile)),
        ])->orderBy('name')->get();

        $videos = Video::published()
            ->when($profile, fn ($q) => $q->forProfile($profile))
            ->with('category')
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%')))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->level))
            ->orderByDesc('published_at')
            ->paginate(12);

        $publicites = Publicite::active()->get();

        return view('apprenant.catalogue', [
            'videos'      => $videos,
            'categories'  => $categories,
            'totalVideos' => $videos->total(),
            'profile'     => $profile,
            'publicites'  => $publicites,
        ]);
    }

    public function show(Request $request, string $slug): View|RedirectResponse
    {
        $video = Video::published()->where('slug', $slug)->with('category')->firstOrFail();
        $user = $request->user();

        if (! $video->is_free && ! $user->isAdmin() && ! $user->hasActiveSubscription()) {
            return redirect()->route('formules')
                ->with('info', 'Un abonnement actif est requis pour accéder à cette vidéo.');
        }

        $canWatch = $user->canWatch($video);

        if ($canWatch) {
            $video->increment('views_count');
        }

        $streamUrl = null;
        if ($canWatch && $video->isUploaded() && $video->file_path) {
            $streamUrl = URL::temporarySignedRoute(
                'videos.stream',
                now()->addHours(3),
                ['video' => $video->id, 'user' => $user->id]
            );
        }

        return view('apprenant.video', compact('video', 'canWatch', 'streamUrl'));
    }

    public function welcome(): \Illuminate\View\View
    {
        $entreprisePlans    = Plan::active()->forProfile(LearnerProfile::Entreprise)->orderBy('price')->get();
        $administrationPlans = Plan::active()->forProfile(LearnerProfile::Administration)->orderBy('price')->get();
        $publicites = Publicite::active()->get();

        $totalUsers         = User::apprenants()->count();
        $totalSubscriptions = Subscription::count();
        $fmt = fn (int $n): string => number_format($n, 0, '.', ' ');

        $statFollowers = $fmt($totalUsers);
        $statLikes     = $fmt($totalUsers + $totalSubscriptions);
        $statCommunity = $fmt($totalUsers + $totalSubscriptions);

        return view('welcome', compact(
            'entreprisePlans', 'administrationPlans', 'publicites',
            'statFollowers', 'statLikes', 'statCommunity'
        ));
    }
}
