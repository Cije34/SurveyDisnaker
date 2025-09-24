<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

<<<<<<< HEAD
        return $request->user()->role === 'admin' ? to_route('admin.dashboard') : to_route('dashboard');
=======
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        if ($user->hasRole('peserta')) {
            return redirect()->intended(route('peserta.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
>>>>>>> 843bcfc (install spatie)
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function createAdmin(){
        return view('admin.login');
    }
}
