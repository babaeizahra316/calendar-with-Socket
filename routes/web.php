<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:admin'])->group(function(){
        Route::get('/dashboard', [App\Http\Controllers\AdminCalendarController::class, 'acceptReject'])->name('admin.dashboard');
        Route::resource('/calendar', App\Http\Controllers\AdminCalendarController::class);
    });
});

Route::group(['middleware' => 'auth:web', 'prefix' => 'user'], function () {
    Route::get('/dashboard', [App\Http\Controllers\CalendarController::class, 'schedule'])->name('user.dashboard');
    Route::resource('/calendar', App\Http\Controllers\CalendarController::class);
});


