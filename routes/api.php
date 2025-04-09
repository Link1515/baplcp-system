<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\SeasonLeaveController;
use App\Http\Controllers\SeasonRegistrationController;
use App\Http\Controllers\EventRegistrationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('seasons', [SeasonController::class, 'index']);
Route::get('seasons/archive', [SeasonController::class, 'archive']);
Route::resource('seasonRegistrations', SeasonRegistrationController::class)->only('store', 'destroy');
Route::post('seasonLeave', [SeasonLeaveController::class, 'store']);
Route::resource('eventRegistrations', EventRegistrationController::class)->only('store', 'destroy');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('seasons', [SeasonController::class, 'store']);
    Route::put('seasons/{season}', [SeasonController::class, 'update']);
    Route::delete('seasons/{season}', [SeasonController::class, 'destroy']);
    Route::put('seasons/{season}/compute', [SeasonController::class, 'compute']);
});
