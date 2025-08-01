<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\TwoFAController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','is_user'])->group(function() {
    Route::get('/user/dashboard', fn() => view('user.dashboard'))->name('user.dashboard');
});
Route::middleware(['auth','is_admin'])->group(function(){
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
});

Route::get('/login',[LoginController::class, 'showFormUser'])->name('login.user');
Route::get('/admin/login',[LoginController::class, 'showFormAdmin'])->name('login.admin');
Route::post('/login',[LoginController::class, 'login'])->name('login.submit');

Route::get('/2fa/verify',[LoginController::class, 'show2FAForm'])->name('2fa.form');
Route::post('/2fa/verify',[LoginController::class, 'verify'])->name('2fa.verify');
// Thiết lập 2FA nếu admin chưa có mã
Route::get('/2fa/setup', [TwoFAController::class, 'setup'])->name('2fa.setup');

Route::get('/signup',[SignupController::class, 'indexUser'])->name('signup.user');
Route::post('/signup',[SignupController::class, 'storeUser'])->name('signup.store.user');

Route::get('/admin/signup',[SignupController::class, 'indexAdmin'])->name('signup.admin');
Route::post('/admin/signup',[SignupController::class, 'storeAdmin'])->name('signup.store.admin');

Route::get('/verify-email',function(){
    return view('auth.verifyEmail');
})->name('verify.email');

Route::get('/email/verify/{id}/{hash}', [SignupController::class , 'verifyEmail'])->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', [SignupController::class, 'send'])->middleware(['throttle:1,1'])->name('verification.send');

Route::get('/forgot-password',[ForgotPasswordController::class, 'index'])->name('forgotpassword.user');
Route::get('/admin/forgot-password',[ForgotPasswordController::class, 'index'])->name('forgotpassword.admin');

