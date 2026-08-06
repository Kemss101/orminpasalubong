<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = [
            'email' => strtolower(trim((string) $validated['email'])),
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended($this->resolveDashboardRoute(Auth::user()));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->input('user_type', 'customer') === 'customer'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['nullable', 'in:admin,seller,customer'],
        ]);

        $normalizedName = trim((string) $validated['name']);
        $normalizedEmail = strtolower(trim((string) $validated['email']));
        $normalizedAddress = isset($validated['address']) ? trim((string) $validated['address']) : null;
        if ($normalizedAddress === '') {
            $normalizedAddress = null;
        }
        $userType = $validated['user_type'] ?? 'customer';

        try {
            $user = User::query()->create([
                'name' => $normalizedName,
                'email' => $normalizedEmail,
                'address' => $userType === 'customer' ? $normalizedAddress : null,
                'password' => Hash::make($validated['password']),
                'user_type' => $userType,
            ]);
        } catch (QueryException $exception) {
            return back()->withErrors([
                'register' => 'Unable to create account right now. Please try again.',
            ])->withInput();
        }

        if (! $user->exists) {
            return back()->withErrors([
                'register' => 'Account was not saved. Please try again.',
            ])->withInput();
        }

        Auth::login($user);

        return redirect()
            ->route($this->resolveDashboardRouteName($user))
            ->with('success', 'Account created successfully and saved to database.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function resolveDashboardRoute(User $user): string
    {
        return route($this->resolveDashboardRouteName($user));
    }

    private function resolveDashboardRouteName(User $user): string
    {
        return match ($user->user_type) {
            'admin' => 'admin.dashboard',
            'seller' => 'seller.dashboard',
            default => 'customer.dashboard',
        };
    }
}
