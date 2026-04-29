<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CheckUserFinishDate extends Command
{
    protected $signature = 'users:check-finish-date';
    protected $description = 'Deactivate users whose finish_date has passed';

    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');

        $updated = User::whereNotNull('finish_date')
            ->whereDate('finish_date', '<', $today)
            ->where('active_status', 1) // only active users
            ->update([
                'active_status' => 0
            ]);

        $this->info("Users deactivated: " . $updated);
    }
}