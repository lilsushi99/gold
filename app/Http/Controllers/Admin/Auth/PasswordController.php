<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordController extends Controller
{
    /** Change password while logged in (also the forced first-login change). */
    public function edit()
    {
        return view('admin.auth.change-password');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'different:current_password', PasswordRule::min(10)->letters()->mixedCase()->numbers()],
        ]);

        $user = $request->user();
        $user->forceFill(['password' => $data['password'], 'must_change_password' => false])->save();
        $request->session()->regenerate();
        ActivityLogger::log('password.changed', $user, 'Password changed');

        return redirect()->route('admin.dashboard')->with('success', 'Password updated.');
    }

    /** Forgot / reset flow. */
    public function request()
    {
        return view('admin.auth.forgot-password');
    }

    public function email(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        Password::sendResetLink($request->only('email'));

        // Same answer whether or not the account exists (no user enumeration).
        return back()->with('status', 'If that email belongs to an administrator, a reset link has been sent.');
    }

    public function reset(Request $request, string $token)
    {
        return view('admin.auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function resetStore(Request $request)
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)->letters()->mixedCase()->numbers()],
        ]);

        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill(['password' => $password, 'must_change_password' => false, 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
            ActivityLogger::log('password.reset', $user, 'Password reset via email link');
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', 'Password reset. You can now log in.')
            : back()->withErrors(['email' => __($status)]);
    }
}
