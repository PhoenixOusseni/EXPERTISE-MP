<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Mail\AbonnementExpire;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Passe au statut "expired" les abonnements actifs dont la date de fin est dépassée';

    public function handle(): int
    {
        $overdue = Subscription::overdue()->with(['user', 'plan'])->get();

        foreach ($overdue as $subscription) {
            $subscription->forceFill(['status' => SubscriptionStatus::Expired])->save();

            try {
                Mail::to($subscription->user->email)
                    ->send(new AbonnementExpire($subscription->user, $subscription));
            } catch (\Exception) {}
        }

        $count = $overdue->count();
        $this->info("{$count} abonnement(s) expiré(s).");

        return self::SUCCESS;
    }
}
