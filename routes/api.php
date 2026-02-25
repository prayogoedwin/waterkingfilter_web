<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\IndexController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'user']);
    Route::get('partner', [IndexController::class, 'partner']);
    Route::get('voucher', [IndexController::class, 'voucher']);
    Route::get('history-order', [IndexController::class, 'historyOrder']);
    Route::get('history-voucher', [IndexController::class, 'historyVoucher']);
    Route::get('detail-voucher/{id}', [IndexController::class, 'detailVoucher']);
    Route::get('counting-partner', [IndexController::class, 'detailPartner']);
});
Route::apiResource('product', ProductController::class);
