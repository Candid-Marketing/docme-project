<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\LinkedAccount;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['admin.components.sidebar', 'user.components.sidebar'], function ($view) {
            $linkedAccounts = [];

            if (Auth::check()) {
                // Determine the real parent account (top of switch stack if exists)
                $activeUserId = Auth::id();

                // If switch stack exists, always use the original top-level user
                if (session()->has('switch_stack') && is_array(session('switch_stack')) && count(session('switch_stack')) > 0) {
                    $activeUserId = session('switch_stack')[0]; // original user who started the switch
                }

                // Get all linked accounts for this main account via LinkedAccount
                $linkedAccounts = LinkedAccount::with(['user.roles', 'linkedUser.roles'])
                    ->where('user_id', $activeUserId)
                    ->get()
                    ->map(function ($account) use ($activeUserId) {
                        return $account->user_id == $activeUserId
                            ? $account->linkedUser
                            : $account->user;
                    })
                    ->filter(function ($account) {
                        // ✅ Only keep users with role_id = 3 (Guest)
                        return Auth::id() !== $account->id &&
                            $account->roles->contains('id', 3);
                    })
                    ->values(); // reindex the collection
 // reindex the collection
            }

            $view->with('linkedAccounts', $linkedAccounts);
        });

    }


}
