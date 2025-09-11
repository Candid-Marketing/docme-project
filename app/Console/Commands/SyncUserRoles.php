<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SyncUserRoles extends Command
{
    protected $signature = 'sync:user-roles';

    protected $description = 'Sync existing users into role_user pivot table based on user_status and attach Guest role to Users only';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Step 1: Sync the user's main role (user_status) as active
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $user->user_status],
                ['is_active' => true]
            );

            // Step 2: Only if the user is a standard User (user_status = 2), attach Guest role
            if ($user->user_status == 2) {
                $hasGuest = DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('role_id', 3)
                    ->exists();

                if (!$hasGuest) {
                    DB::table('role_user')->insert([
                        'user_id'    => $user->id,
                        'role_id'    => 3, // Guest
                        'is_active'  => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->info('Users have been synced. Guest role added to Users only.');
    }
}
