<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\AccountVerification;
use App\Models\PasswordResetToken;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function verifyAccount(Request $request): View
    {
        return view('auth.verify-account');
    }



    public function processAccountVerification(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'verification_code' => ['required', 'string'],
        ]);

        $verificationToken = AccountVerification::where('token', $validated['verification_code'])->first();

        if (!$verificationToken) {
            return redirect()->route('verify.account')->with('error', 'Invalid verification token.');
        }

        if ($verificationToken->expires_at < now()) {

            $verificationToken->delete();

            return redirect()->route('verify.account')->with('error', 'The token has expired, Kindly request a new one');
        }

        $user = User::find($verificationToken->user_id);


        if (!$user) {
            return redirect()->route('verify.account')->with('error', 'User not found.');
        }

        $user->email_verified_at = now();
        $user->status = true;
        $user->save();

        $pass = PasswordResetToken::createResetTokenForUser($user->email);

        // Delete the used token
        // $verificationToken->delete();



        return redirect()->route('password.reset', ['user'=> $user, 'token' =>$pass->token])->with('status', 'Your account has been verified. Please setup a password for your account.');
    }
}
