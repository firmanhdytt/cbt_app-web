<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectToGoogle(Request $request)
    {
        // Simpan mode autentikasi ('login' atau 'link') ke dalam session
        $mode = $request->query('mode', 'login');
        session(['google_auth_mode' => $mode]);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());
            $route = Auth::check() ? 'profile.edit' : 'login';
            $errorKey = Auth::check() ? 'google' : 'username';
            return redirect()->route($route)->withErrors([
                $errorKey => 'Gagal melakukan autentikasi menggunakan Google. Silakan coba lagi.',
            ]);
        }

        $mode = session('google_auth_mode', 'login');

        if ($mode === 'link') {
            // Flow: Link Google Account
            if (! Auth::check()) {
                return redirect()->route('login')->withErrors([
                    'username' => 'Silakan login terlebih dahulu untuk menghubungkan akun Gmail.',
                ]);
            }

            $currentUser = Auth::user();

            // Cek apakah akun Google ini sudah dikaitkan dengan user lain
            $existingUser = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($existingUser && $existingUser->id !== $currentUser->id) {
                return redirect()->route('profile.edit')->withErrors([
                    'google' => 'Akun Gmail ini sudah dikaitkan dengan akun pengguna lain.',
                ]);
            }

            // Kaitkan Google account ke user aktif
            $currentUser->update([
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'email_verified_at' => now(),
                'avatar' => $currentUser->avatar ?? $googleUser->getAvatar(),
            ]);

            return redirect()->route('profile.edit')->with('status', 'google-linked');

        } else {
            // Flow: Login (Cari user berdasarkan google_id atau email)
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Perbarui google_id, email, avatar, dan verifikasi email otomatis dari Google
                $user->update([
                    'google_id'         => $user->google_id ?? $googleUser->getId(),
                    'email'             => $user->email ?? $googleUser->getEmail(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                    'avatar'            => $user->avatar ?? $googleUser->getAvatar(),
                ]);

                Auth::login($user);
                return redirect()->intended(route('dashboard', absolute: false));
            } else {
                // Jika akun belum terdaftar, BUATKAN AKUN BARU SISWA OTOMATIS!
                $email = $googleUser->getEmail();
                $baseUsername = strtolower(explode('@', $email)[0]);
                $username = preg_replace('/[^a-z0-9_]/', '', $baseUsername);
                if (empty($username)) {
                    $username = 'user_' . rand(1000, 9999);
                }

                // Pastikan username unik
                $originalUsername = $username;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $originalUsername . $counter;
                    $counter++;
                }

                $newUser = User::create([
                    'name'              => $googleUser->getName() ?? 'Peserta CBT',
                    'email'             => $email,
                    'username'          => $username,
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'role'              => 'siswa',
                    'email_verified_at' => now(), // Email dari Google sudah terverifikasi resmi!
                    'password'          => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                ]);

                // Assign Spatie Role 'siswa' (safely create if not exists)
                if (!\Spatie\Permission\Models\Role::where('name', 'siswa')->exists()) {
                    \Spatie\Permission\Models\Role::create(['name' => 'siswa']);
                }
                $newUser->assignRole('siswa');

                Auth::login($newUser);
                return redirect()->route('siswa.dashboard')->with('success', 'Selamat datang! Akun Anda berhasil dibuat & diverifikasi otomatis melalui Google GMail.');
            }
        }
    }

    /**
     * Unlink Google account from the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlinkGoogle(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->update([
                'google_id' => null,
            ]);
            return redirect()->route('profile.edit')->with('status', 'google-unlinked');
        }

        return redirect()->route('login');
    }
}
