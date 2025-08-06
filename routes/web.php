<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\TwoFAController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\ChangePasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function(){
    return view('home');
});

Route::middleware(['auth','is_user'])->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');

    Route::get('/change-password',[ChangePasswordController::class ,'showFormChange'])->name('changepassword.user.form');
    Route::post('/change-password',[ChangePasswordController::class ,'changePassword'])->name('changepassword.user');

    Route::get('/profile', [HomeController::class, 'showProfile'])->name('profile.edit');
    Route::post('/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
});
Route::middleware(['auth','is_admin'])->group(function(){
    Route::get('/admin/dashboard', [AdminDashboardController::class , 'index'])->name('admin.dashboard');

    Route::get('/admin/profile', [AdminDashboardController::class, 'showProfile'])->name('admin.profile.edit');
    Route::post('/admin/profile', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');

    Route::get('/admin/change-password',[ChangePasswordController::class,'showFormChangeAdmin'])->name('admin.changepassword.form');
    Route::post('/admin/change-password',[ChangePasswordController::class,'changePassword'])->name('admin.changepassword');

    Route::middleware('2fa_confirmed')->group(function(){
    Route::get('/admin/list-account', [AdminDashboardController::class, 'listAccount'])->name('admin.list-account');
    Route::get('/admin/account/{id}/detail', [AdminDashboardController::class, 'detailAccount'])->name('admin.detail-account');
    Route::delete('/admin/account/{id}/destroy',[AdminDashboardController::class, 'destroy'])->name('admin.account.destroy');
    Route::post('/admin/account/{id}/lock', [AdminDashboardController::class, 'updateAccountStatus']);
    Route::post('/admin/account/{id}/unlock', [AdminDashboardController::class, 'updateAccountStatus']);
});

});

Route::get('/2fa/sensitive-verify', [LoginController::class, 'show2FAForm'])->name('2fa.sensitive.form');
Route::post('/2fa/sensitive-verify', [LoginController::class, 'verify'])->name('2fa.sensitive.verify');


Route::get('/login',[LoginController::class, 'index'])->name('login');
Route::post('/login',[LoginController::class, 'login'])->name('login.submit');

Route::get('/2fa/verify',[LoginController::class, 'show2FAForm'])->name('2fa.form');
Route::post('/2fa/verify',[LoginController::class, 'verify'])->name('2fa.verify');
// Thiết lập 2FA nếu admin chưa có mã
Route::get('/2fa/setup', [TwoFAController::class, 'setup'])->name('2fa.setup');
Route::post('/2fa/verify-setup', [TwoFAController::class, 'verifySetup'])->name('2fa.verify-setup');

Route::get('/signup',[SignupController::class, 'indexUser'])->name('signup.user');
Route::post('/signup',[SignupController::class, 'storeUser'])->name('signup.store.user');

Route::get('/admin/signup',[SignupController::class, 'indexAdmin'])->name('signup.admin');
Route::post('/admin/signup',[SignupController::class, 'storeAdmin'])->name('signup.store.admin');

Route::post('/logout', function () {
    Auth::logout();
    session()->forget(['2fa_confirmed', '2fa:admin:id']);
    return redirect()->route('login')->with('success', 'Đăng xuất thành công');
})->name('logout');

Route::get('/verify-email',function(){
    return view('auth.verifyEmail');
})->name('verify.email');

Route::get('/email/verify/{id}/{hash}', [SignupController::class , 'verifyEmail'])->middleware(['auth','signed'])->name('verification.verify');

Route::post('/email/verification-notification', [SignupController::class, 'send'])->middleware(['auth','throttle:1,1'])->name('verification.send');


Route::get('/forgot-password',[ForgotPasswordController::class, 'showFormForgot'])->name('forgotpassword');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgotpassword.send');

Route::get('/reset-password/{token}', [ForgotPasswordController:: class, 'showFormReset'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController:: class, 'reset'])->name('password.update');

