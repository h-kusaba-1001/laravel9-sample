<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
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

Route::get('/', function () {
    return view('welcome');
});

// from laravel/ui
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// admin
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () { 
    // auth
    Route::group(['controller' => AdminLoginController::class, 'middleware' => 'guest:admin'], function() {
        Route::get('/login', 'showLoginForm')->name('show_login');
        Route::post('/login', 'login')->name('login');
    });

    // logined
    Route::group(['middleware' => 'auth:admin'], function () { 
        // logout
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        
        Route::view('/', 'admin.home')->name('home');
    });
});
