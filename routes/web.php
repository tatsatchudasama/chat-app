<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('registration-view', [LoginController::class, 'registration_view'])->name('registration_view');
Route::post('registration', [LoginController::class, 'registration'])->name('registration');

Route::get('login-view', [LoginController::class, 'login_view'])->name('login_view');
Route::post('login', [LoginController::class, 'login'])->name('login');


Route::middleware(['auth_check'])->group(function () {
    
    Route::get('index', [DashboardController::class, 'index'])->name('dashboard_index');



});
