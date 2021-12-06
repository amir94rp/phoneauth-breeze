<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use PhoneAuth\Support\Verification\PhoneNumber;

class ResetPasswordController extends Controller
{
    public function create(Request $request){
        return Inertia::render('Auth/ResetPassword' , [
            'status' => session('status'),
            'number' => $request->query('number')
        ]);
    }

    public function store(Request $request){

        $validated = $request->validate([
            'token' => ['required' , 'digits:6'],
            'number' => ['required' , config('phoneauth.validate') , 'exists:users,number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = PhoneNumber::verifyToken(
            [
                'number' => $validated['number'] ,
                'token' => $validated['token']
            ]
        );

        if ($status == PhoneNumber::NUMBER_VERIFIED){
            $user = User::where('number' , '=' , $validated['number'])->first();
            $user->forceFill([
                'password' => Hash::make($validated['password']),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
            return redirect()->route('login')->with('status', __('passwords.reset'));
        }

        throw ValidationException::withMessages([
            'token' => [trans($status)],
        ]);
    }
}
