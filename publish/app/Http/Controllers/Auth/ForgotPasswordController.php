<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use PhoneAuth\Auth\Verification\PhoneNumber;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    /**
     * Display the password reset token request view.
     *
     */
    public function create()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => ['required' , config('phoneauth.validate') , 'exists:users,number'],
        ]);

        // We will send the number verification token to this user. Once we have attempted
        // to send the token, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = PhoneNumber::sendVerificationToken(
            $request->only('number')
        );

        if ($status == PhoneNumber::VERIFICATION_TOKEN_SENT) {
            return redirect(
                URL::temporarySignedRoute(
                    'password.create',
                    now()->addMinutes(15),
                    $request->only('number')
                )
            )->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'number' => [trans($status)],
        ]);
    }
}
