<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::prefix('contracts')->middleware(['auth:sanctum'])->group(function () {

        Route::post('/', [ContractController::class, 'store'])
            ->name('contracts.store');

        Route::get('/', [ContractController::class, 'index'])
            ->name('contracts.index');

        Route::post('{contract}/invoices', [InvoiceController::class, 'store'])
            ->name('contracts.invoices.store');

        Route::get('{contract}/invoices', [InvoiceController::class, 'index'])
            ->name('contracts.invoices.index');

        Route::get('{contract}/summary', [InvoiceController::class, 'summary'])
            ->name('contracts.summary');
    });

    Route::prefix('invoices')->middleware(['auth:sanctum'])->group(function () {
        Route::get('{invoice}', [InvoiceController::class, 'show'])
            ->name('invoices.show');

        Route::post('{invoice}/payments', [InvoiceController::class, 'recordPayment'])
            ->name('invoices.payments.store');
    });
});
