<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'birthday' => [
            'required',
            'date',
            function ($attribute, $value, $fail) {

                if ($value > now()->format('Y-m-d')) {
                    $fail('Future dates are not allowed.');
                }

                if ($value > now()->subYears(18)->format('Y-m-d')) {
                    $fail('Age must be at least 18 years.');
                }
            },
        ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'role' => 'customer',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to($this->redirectPathFor($user))
            ->with('success', 'Register successful');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $key = Str::lower($request->email) . '|' . $request->ip();

    // 3 attempts max
    if (RateLimiter::tooManyAttempts($key, 2)) {

        $seconds = RateLimiter::availableIn($key); // remaining lock time

        return back()->with([
            'error' => 'Account locked for 24 hours due to multiple failed attempts.',
            'lock_seconds' => $seconds,
        ]);
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        RateLimiter::clear($key); // reset on success

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathFor($request->user()))
            ->with('success', 'Login successful');
    }

    // failed attempt → lock after 3 attempts for 24 hours
    RateLimiter::hit($key, 86400); // 24 hours

    return back()->with('error', 'Invalid credentials');
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectPathFor(User $user): string
    {
        return match ($user->role) {
            'seller' => route('seller.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('shop.index'),
        };
    }
}
