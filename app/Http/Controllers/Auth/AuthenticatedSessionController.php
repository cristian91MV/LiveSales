<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
     *
     * Calls $request->authenticate() which centralizes throttle logic and authentication.
     * If ValidationException is thrown, Laravel handles it automatically and redirects to login with errors.
     * If authenticate() returns without exception, the session is already open.
     * Then checks Auth::user()->is_active:
     * - If false: logout, invalidate session, regenerate token, and return error.
     * - If true: regenerate session and redirect to dashboard.
     */
    public function store(LoginRequest $request)
    {
        // Call authenticate() which centralizes all throttle and authentication logic.
        // If ValidationException is thrown, Laravel catches it and redirects automatically.
        // If it returns without exception, the session is already open.
        $request->authenticate();

        // At this point, authentication succeeded and the session is open.
        // Now check if the user is active.
        if (!Auth::user()->is_active) {
            // User is inactive: logout and invalidate the session.
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect back to login with error message.
            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está desactivada.']);
        }

        // User is active: regenerate the session and redirect to dashboard.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     *
     * Logs out the user, invalidates the session, regenerates the token,
     * and redirects to the login page.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
