<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
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

Route::get('/welcome', function () {
    return view('welcome');
});
Route::resource('/', HomeController::class)->only(['index']);
Route::resource('/about', AboutController::class)->only(['index']);
Route::resource('/article', ArticleController::class)->only(['index']);
Route::resource('/home', HomeController::class)->only(['index']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::prefix('member')->group(function () {
        Route::get('/home', function () {
            return view('member.my-posts');
        })->name('dashboard');
    });
});
