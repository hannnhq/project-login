<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


Route::get('/login',[LoginController::class, 'index'])->name('login');

Route::get('/signup',[SignupController::class, 'index'])->name('signup');
Route::post('/signup',[SignupController::class, 'store'])->name('signup.store');

Route::get('/verify-email',function(){
    return view('auth.verifyEmail');
})->name('verify.email');

Route::get('/email/verify/{id}/{hash}', [SignupController::class , 'verifyEmail'])->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', [SignupController::class, 'send'])->middleware(['throttle:1,1'])->name('verification.send');

Route::get('/forgot-password',[ForgotPasswordController::class, 'index'])->name('forgotpassword');

