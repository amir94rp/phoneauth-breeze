<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use PhoneAuth\Support\Verification\PhoneNumber;

class PhoneVerificationController extends Controller
{
    public function create(Request $request){

        if ($request->user()->hasVerifiedNumber()){
            return redirect(RouteServiceProvider::HOME);
        }

        return Inertia::render('Auth/VerifyNumber' , [
            'status' => session('status') ,
            'number' => $request->query('number')
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'token' => ['required' , 'digits:6']
        ]);

        $status = PhoneNumber::verifyToken(
            [
                'number' => $request->user()->number,
                'token' => $validated['token']
            ]
        );

        if ($status == PhoneNumber::NUMBER_VERIFIED){
            $request->user()->markNumberAsVerified();
            return redirect(RouteServiceProvider::HOME);
        }

        throw ValidationException::withMessages([
            'token' => [trans($status)],
        ]);
    }
}
