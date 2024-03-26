<?php

namespace App\Http\Controllers;

use App\Models\SocialiteLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteLoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $socialiteUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $socialiteUser->getEmail()],
            ['name' => $socialiteUser->getName()]
        );
        SocialiteLogin::firstOrCreate(
            ['user_id' => $user->id, 'provider' => 'google'],
            ['provider_id' => $socialiteUser->getId()]
        );

        auth()->login($user);
        return to_route('home');
    }
}
