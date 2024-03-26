<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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


Route::middleware('auth:sanctum')->group(function () {
   
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/account', [AccountController::class, 'accountAction']);
    Route::post('/account', [AccountController::class, 'createAccount']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/sender', [TransactionController::class, 'sender']);
});

Route::post('/login', [UserController::class, 'login']);
