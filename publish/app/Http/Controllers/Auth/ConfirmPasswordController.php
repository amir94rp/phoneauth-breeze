<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ConfirmPasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     */
    public function create()
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'password' => ['required' , 'min:8']
        ]);


        if (! Auth::guard('web')->validate([
            'number' => $request->user()->number,
            'password' => $validated['password'],
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}