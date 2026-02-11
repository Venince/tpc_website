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

        $user = $request->user();

        // If an old intended URL is "/dashboard", remove it
        $intended = $request->session()->get('url.intended');
        if ($intended) {
            $path = parse_url($intended, PHP_URL_PATH) ?? '';
            if (rtrim($path, '/') === '/dashboard') {
                $request->session()->forget('url.intended');
            }
        }

        // ✅ Admin rule: ALWAYS land on admin dashboard (/tpc_admin)
        if ($user && $user->can('access-admin')) {
            $request->session()->forget('url.intended');
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
