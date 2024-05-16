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
    
    Route::post('logout', [LoginController::class, 'logout'])->name('dashboard_logout');

    Route::get('friends-list', [DashboardController::class, 'user_list'])->name('user_list');
    
    Route::get('user-search', [DashboardController::class, 'user_search'])->name('user_search');

    Route::get('edit-user/{id}', [DashboardController::class, 'edit_user'])->name('edit_user');

    Route::post('update-user/{id}', [DashboardController::class, 'update_user'])->name('update_user');

});
