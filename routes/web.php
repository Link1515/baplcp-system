<?php

use App\Http\Controllers\EventGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

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

Route::view('/', 'index');

Route::get('/login/line', [LoginController::class, 'redirectToProvider']);
Route::get('/login/line/callback', [LoginController::class, 'handleProviderCallback']);

Route::prefix('admin')->group(function () {
    Route::view('/', 'admin.index')->name('admin.index');
    Route::resource('eventGroups', EventGroupController::class);
});
