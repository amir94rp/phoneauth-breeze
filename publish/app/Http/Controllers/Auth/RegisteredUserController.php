<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use PhoneAuth\Support\Verification\PhoneNumber;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required' , 'string' , 'max:255'],
            'number' => ['required' , config('phoneauth.validate') , 'unique:users,number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'number' => $validated['number'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $status = PhoneNumber::sendVerificationToken(
            [
                'number' => $user->number
            ]
        );

        return redirect(
            URL::temporarySignedRoute(
                'verify.create',
                now()->addMinutes(15) ,
                ['number' => $user->number]
            )
        )->with('status', __($status));
    }
}
