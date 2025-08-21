<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailManagementController;
use App\Http\Controllers\EmailSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('clear-cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:cache');
    \Artisan::call('optimize:clear');
	Log::info('Clear all cache');
    dd("Cache is cleared");
});
Route::get('db-migrate', function () {
    \Artisan::call('migrate');
    dd("Database migrated");
});
Route::get('db-seed', function () {
    \Artisan::call('db:seed');
    dd("Database seeded");
});
Route::get('/', [ProfileController::class, 'welcome']);

Route::get('lang/home', [LangController::class, 'index']);
Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');


Route::middleware(['auth','verified'])->group(function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/pricing-plans', [DashboardController::class, 'pricing_plans'])->name('pricing-plans');
	
	Route::get('/portfolio-desires', [DashboardController::class, 'portfolio_desires'])->name('portfolio-desires');
	Route::post('/portfolio-desires', [DashboardController::class, 'portfolio_desires_save'])->name('portfolio-desires');
	
	Route::get('/current-financial-account', [DashboardController::class, 'current_financial_account'])->name('current-financial-account');
	Route::post('/current-financial-account', [DashboardController::class, 'current_financial_account_save'])->name('current-financial-account');
	Route::post('/delete-current-financial-account', [DashboardController::class, 'delete_current_financial_account'])->name('delete-current-financial-account');
	
	
	Route::get('/income-sources', [DashboardController::class, 'income_sources'])->name('income-sources');
	Route::post('/income-sources', [DashboardController::class, 'income_sources_save'])->name('income-sources');
	Route::post('/delete-income-source', [DashboardController::class, 'delete_income_source'])->name('delete-income-source');
	
	Route::get('/roth-calculator', [DashboardController::class, 'roth_calculator'])->name('roth-calculator');
	Route::post('/roth-calculator', [DashboardController::class, 'roth_calculator_save'])->name('roth-calculator');
	Route::post('/delete-roth-calculator', [DashboardController::class, 'delete_roth_calculator'])->name('delete-roth-calculator');
	
});
	Route::get('/income-plan-pdf', [PdfController::class, 'incomePlan']);



require __DIR__.'/auth.php';
require __DIR__.'/backend.php';
