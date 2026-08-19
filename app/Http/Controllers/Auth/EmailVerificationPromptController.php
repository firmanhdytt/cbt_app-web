<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        if ($user->role === 'admin') {
            $dashboardRoute = route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            $dashboardRoute = route('guru.dashboard');
        } else {
            $dashboardRoute = route('siswa.dashboard');
        }

        return $user->hasVerifiedEmail()
                    ? redirect()->intended($dashboardRoute)
                    : view('auth.verify-email');
    }
}
