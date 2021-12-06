<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhoneAuth\Support\Verification\PhoneNumber;

class ResendVerificationToken extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'number' => ['required' , config('phoneauth.validate') , 'exists:users,number']
        ]);

        $status = PhoneNumber::sendVerificationToken(
            [
                'number' => $validated['number']
            ]
        );

        return redirect()->back()
            ->with('status' , __($status));
    }
}
