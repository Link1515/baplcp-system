<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventGroupController;
use App\Http\Controllers\EventGroupRegistrationController;
use App\Http\Controllers\EventRegistrationController;
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

Route::view('/', 'index')->name('home');

Route::get('/login/line', [LoginController::class, 'redirectToProvider']);
Route::get('/login/line/callback', [LoginController::class, 'handleProviderCallback']);

Route::resource('eventGroups', EventGroupController::class)->only(['index', 'show']);
Route::get('eventGroups/{eventGroup}/register', [EventGroupController::class, 'register'])->name('eventGroups.register');
Route::get('events/{event}/register', [EventController::class, 'register'])->name('events.register');
Route::post('eventGroupRegistrations', [EventGroupRegistrationController::class, 'store'])->name('eventGroupRegistrations.store');
Route::post('eventRegistrations', [EventRegistrationController::class, 'store'])->name('eventRegistrations.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.index')->name('index');
    Route::get('eventGroups', [EventGroupController::class, 'adminIndex'])->name('eventGroups.index');
    Route::resource('eventGroups', EventGroupController::class)->except(['index', 'show']);
});
