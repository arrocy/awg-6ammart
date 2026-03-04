<?php

use Illuminate\Support\Facades\Route;
use Modules\AwgCloud\Http\Controllers\AwgCloudController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('awgclouds', AwgCloudController::class)->names('awgcloud');
});

Route::post('awg-webhook', [AwgCloudController::class, 'webhook']);