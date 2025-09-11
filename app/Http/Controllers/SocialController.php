<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SocialController extends Controller
{
    public function redirect($provider)
    {

        if ($provider === 'linkedin') {
            return Socialite::driver($provider)->scopes([
                'r_liteprofile', 'r_emailaddress'
            ])->redirect();
        }
        else
        {
            return Socialite::driver($provider)->redirect();
        }
    }

    public function callback($provider)
    {

        $socialUser = Socialite::driver($provider)->user();
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            session()->flash('social_existing_user', true);
        } else {
            $user = User::create([
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'password' => Hash::make('password123'), // Default password
                'user_status' => 2,
                'is_verified' => 1,
                'first_name' => $socialUser->user['given_name'] ?? null,
                'last_name' => $socialUser->user['family_name'] ?? null,
                'status' => 0,
                'provider_id' => $socialUser->getId(),
            ]);

            session()->flash('social_new_user', $provider);
        }

        Auth::login($user, true);
        return redirect()->route('login');
    }
}
