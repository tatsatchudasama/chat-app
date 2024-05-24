<?php

use App\Http\Controllers\Auth\forgatPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\chatUserController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\FriendRequestController;
use App\Http\Controllers\Dashboard\friend_request_list;
use App\Http\Controllers\Dashboard\FriendRequestList;
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

Route::get('registration-view', [LoginController::class, 'registration_view'])->name('registration_view');
Route::post('registration', [LoginController::class, 'registration'])->name('registration');

Route::get('', [LoginController::class, 'login_view'])->name('login_view'); //login-view
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::post('password-change-email', [forgatPasswordController::class, 'password_change_email'])->name('password_change_email');

Route::get('password-reset-view/{token}', [forgatPasswordController::class, 'password_reset_view'])->name('password_reset_view');
Route::post('password-reset/{token}', [forgatPasswordController::class, 'password_reset'])->name('password_reset');

Route::middleware(['auth_check'])->group(function () {

    Route::get('index', [DashboardController::class, 'index'])->name('dashboard_index');

    Route::post('logout', [LoginController::class, 'logout'])->name('dashboard_logout');

    Route::get('friends-list', [DashboardController::class, 'user_list'])->name('user_list');

    Route::get('user-search', [DashboardController::class, 'user_search'])->name('user_search');

    Route::get('edit-user/{id}', [DashboardController::class, 'edit_user'])->name('edit_user');

    Route::post('update-user/{id}', [DashboardController::class, 'update_user'])->name('update_user');

    Route::post('fried-request', [FriendRequestController::class, 'create_friend_request'])->name('fried_request');
    Route::post('un-friend-request', [FriendRequestController::class, 'un_friend_request'])->name('un_friend_request');


    Route::get('friend-request-list', [FriendRequestList::class, 'friend_request_list'])->name('friend_request_list');

    Route::post('accept-request', [FriendRequestList::class, 'accept_request'])->name('accept_request');
    Route::post('un-accept-request', [FriendRequestList::class, 'un_accept_request'])->name('un_accept_request');

    Route::post('reject-request', [FriendRequestList::class, 'reject_request'])->name('reject_request');
    
    Route::get('chat-view', [chatUserController::class, 'chat_view'])->name('chat_view');

    Route::post('chat', [chatUserController::class, 'chat'])->name('chat');

    Route::get('get-message', [chatUserController::class, 'get_message'])->name('get_message');

});
