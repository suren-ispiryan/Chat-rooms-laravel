<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Guest
Route::post('register', [SignController::class, 'register']);
Route::post('login', [SignController::class, 'login']);
// User
Route::middleware('auth:sanctum')->group(function () {
    // Sign
    Route::get('logout', [SignController::class, 'logOut']);
});
