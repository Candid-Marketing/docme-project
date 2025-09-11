<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RevertGuestRoles extends Command
{
    protected $signature = 'revert:guest-roles';
    protected $description = 'Remove Guest role (role_id = 3) from users with user_status = 2';

    public function handle()
    {
        $userIds = User::where('user_status', 2)->pluck('id');

        $deleted = DB::table('role_user')
            ->whereIn('user_id', $userIds)
            ->where('role_id', 3)
            ->delete();

        $this->info("Removed {$deleted} Guest role(s) from users with user_status = 2.");
    }
}
