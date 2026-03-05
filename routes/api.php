<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\IndexController;
use App\Http\Controllers\API\PartnerAuthController;
use App\Http\Controllers\API\PartnerController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\VoucherScanController;
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
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::put('profile/password', [AuthController::class, 'updatePassword']);
    Route::get('partner', [IndexController::class, 'partner']);
    Route::get('voucher', [IndexController::class, 'voucher']);
    Route::get('history-order', [IndexController::class, 'historyOrder']);
    Route::get('history-voucher', [IndexController::class, 'historyVoucher']);
    Route::get('detail-voucher/{id}', [IndexController::class, 'detailVoucher']);
});
Route::apiResource('product', ProductController::class);
Route::prefix('partner')->group(function () {
    Route::middleware('auth:partner-api')->group(function () {
        Route::get('me', [PartnerAuthController::class, 'me']);
        Route::get('counting-partner', [IndexController::class, 'detailPartner']);
        Route::post('update-password', [PartnerAuthController::class, 'updatePassword']);
        Route::post('voucher/scan', [VoucherScanController::class, 'scanBarcode']);
        Route::post('voucher/preview', [VoucherScanController::class, 'previewBarcode']);

        Route::get('wallet', [PartnerController::class, 'wallet']);
        Route::post('wallet/withdraw', [PartnerController::class, 'withdraw']);

        // History
        Route::get('wallet/history-keuangan', [PartnerController::class, 'historyKeuangan']);
        Route::get('wallet/history-pendapatan', [PartnerController::class, 'historyPendapatan']);
    });
    Route::post('login', [PartnerAuthController::class, 'login']);
});
