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
        // Memulai login sosial dengan provider Google
        // Ini akan mengalihkan pengguna ke halaman login Google untuk otorisasi.
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        // Mendapatkan informasi pengguna dari Google setelah otorisasi berhasil
        $socialiteUser = Socialite::driver('google')->user();

        // Mencari atau membuat pengguna lokal berdasarkan alamat email Google
        $user = User::firstOrCreate(
            ['email' => $socialiteUser->getEmail()],
            ['name' => $socialiteUser->getName()]
        );

        // (Opsional) Membuat catatan terkait di tabel SocialiteLogin
        // Ini berguna untuk melacak penyedia login sosial yang berbeda yang digunakan oleh pengguna.
        SocialiteLogin::firstOrCreate(
            ['user_id' => $user->id, 'provider' => 'google'],
            ['provider_id' => $socialiteUser->getId()]
        );

        // Mengotentikasi pengguna di aplikasi lokal
        auth()->login($user);
        return to_route('home');
    }
}
