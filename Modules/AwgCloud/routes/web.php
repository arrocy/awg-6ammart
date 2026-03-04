<?php

use Illuminate\Support\Facades\Route;
use Modules\AwgCloud\Http\Controllers\AwgCloudController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('awgclouds', AwgCloudController::class)->names('awgcloud');
});
Route::prefix('admin')->group(function () {
    Route::get('/awgcloud', [AwgCloudController::class, 'index']) ->name('admin.awgcloud.index');
    Route::post('/awgcloud', [AwgCloudController::class, 'update']) ->name('admin.awgcloud.update');
});
