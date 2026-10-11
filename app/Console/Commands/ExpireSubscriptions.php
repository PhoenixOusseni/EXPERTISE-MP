<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Passe au statut "expired" les abonnements actifs dont la date de fin est dépassée';

    public function handle(): int
    {
        $count = Subscription::overdue()->update(['status' => SubscriptionStatus::Expired]);

        $this->info("{$count} abonnement(s) expiré(s).");

        return self::SUCCESS;
    }
}
