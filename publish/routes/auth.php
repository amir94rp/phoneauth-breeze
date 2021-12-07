<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\PhoneVerificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResendVerificationToken;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

//Authenticated session controller
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

//registered user controller
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');


//verify phone number
Route::get('/verify' , [PhoneVerificationController::class , 'create'])
    ->middleware('auth' , 'signed')
    ->name('verify.create');

Route::post('/verify' , [PhoneVerificationController::class , 'store'])
    ->middleware('auth' , 'signed')
    ->name('verify.store');


//resend verification token
Route::post('/resend' , [ResendVerificationToken::class , '__invoke'])
    ->middleware('throttle:6,1')
    ->name('resend');


//forgot password controller
Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('forgot.create');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('forgot.store');


//reset password controller
Route::get('reset-password' , [ResetPasswordController::class , 'create'])
    ->middleware('guest' , 'signed')
    ->name('password.create');

Route::post('reset-password' , [ResetPasswordController::class , 'store'])
    ->middleware('guest' , 'signed' , 'throttle:6,1')
    ->name('password.store');


//confirm password controller
Route::get('/confirm-password', [ConfirmPasswordController::class, 'create'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmPasswordController::class, 'store'])
    ->middleware('auth');
