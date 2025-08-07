
<?php

use App\Http\Controllers\Admin\EmailManagementController;
use App\Http\Controllers\Admin\EmailSettingsController;
// use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
	Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
		Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
		
		//ChangePassword
		// Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
		// Route::post('/change-password', [ChangePasswordController::class, 'save_data'])->name('change-password-save');

		//EmailSettings
		Route::get('/email-settings', [EmailSettingsController::class, 'index'])->name('email-settings');
		Route::post('/email-settings', [EmailSettingsController::class, 'save_data'])->name('email-settings-save');

		// Email Management Routes
		Route::get('email-management', [EmailManagementController::class,'index'])->name('email-management');
		Route::get('/email-management-edit/{id}', [EmailManagementController::class, 'email_management_edit'])->name('email-management-edit');
		Route::post('/email-management-edit-save',[EmailManagementController::class,'manage_email_management_process'])->name('email-management-edit-save');
	});

	Route::prefix('admin')->name('admin.')->group(function () {
		Route::middleware('admin_guest')->group(function () {
			Route::get('/tokenlogin', [AuthenticatedSessionController::class, 'loginWithToken']);
			
			Route::get('register', [RegisteredUserController::class, 'create'])
						->name('register');

			Route::post('register', [RegisteredUserController::class, 'store']);

			Route::get('login', [AuthenticatedSessionController::class, 'create'])
						->name('login');

			Route::post('login', [AuthenticatedSessionController::class, 'store']);

			Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
						->name('password.request');

			Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
						->name('password.email');

			Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
						->name('password.reset');

			Route::post('reset-password', [NewPasswordController::class, 'store'])
						->name('password.store');
		});

		Route::middleware('auth:admin')->group(function () {
			Route::get('verify-email', EmailVerificationPromptController::class)
						->name('verification.notice');

			Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
						->middleware(['signed', 'throttle:6,1'])
						->name('verification.verify');

			Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
						->middleware('throttle:6,1')
						->name('verification.send');

			Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
						->name('password.confirm');

			Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

			Route::put('password', [PasswordController::class, 'update'])->name('password.update');

			Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
						->name('logout');
			Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
		});
	});
});





