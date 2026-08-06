<?php

namespace App\Http\Controllers;

use App\Models\Cashback;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google');
        }

        // Check if user exists
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            // User exists, log them in
            Auth::login($user);
            return redirect()->route('customer.dashboard')->with('success', 'Logged in successfully with Google');
        }

        // Check if email already exists
        $existingUser = User::where('email', $googleUser->email)->first();

        if ($existingUser) {
            // Email exists, link Google account
            $existingUser->update([
                'google_id' => $googleUser->id,
                'auth_provider' => 'google',
                'profile_picture' => $googleUser->avatar,
            ]);
            Auth::login($existingUser);
            return redirect()->route('customer.dashboard')->with('success', 'Google account linked and logged in successfully');
        }

        // Create new user
        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'profile_picture' => $googleUser->avatar,
            'user_type' => 'customer',
            'auth_provider' => 'google',
            'address' => '', // User will update this later
            'password' => bcrypt(str_random(32)), // Generate random password for Google users
        ]);

        // Create cashback account for new user
        Cashback::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Account created and logged in successfully with Google')
            ->with('complete_profile', true);
    }

    /**
     * Link Google account to existing user
     */
    public function linkGoogleAccount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle linking Google account
     */
    public function handleLinkGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('customer.dashboard')->with('error', 'Failed to link Google account');
        }

        $user = auth()->user();

        // Check if this Google account is already linked to another user
        if (User::where('google_id', $googleUser->id)->where('id', '!=', $user->id)->exists()) {
            return redirect()->route('customer.dashboard')->with('error', 'This Google account is already linked to another user');
        }

        $user->update([
            'google_id' => $googleUser->id,
            'auth_provider' => 'google',
            'profile_picture' => $googleUser->avatar,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Google account linked successfully');
    }

    /**
     * Unlink Google account
     */
    public function unlinkGoogleAccount()
    {
        $user = auth()->user();

        if (!$user->google_id) {
            return redirect()->route('customer.dashboard')->with('error', 'No Google account linked');
        }

        if (!$user->password || $user->password === '') {
            return redirect()->route('customer.dashboard')->with('error', 'Please set a password before unlinking Google account');
        }

        $user->update([
            'google_id' => null,
            'auth_provider' => 'local',
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Google account unlinked successfully');
    }

}
