<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/receiving', [\App\Http\Controllers\ReceiverController::class, 'receiving']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrdersController::class, 'getOrders']);
    Route::get('/customer', [CustomerController::class, 'getCustomers']);

    // User
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{userId}', [UserController::class, 'destroy']);
});


Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.post');
