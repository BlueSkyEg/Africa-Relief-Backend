<?php

use App\Http\Controllers\Dashboard\CarouselDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::resource('carousels', CarouselDashboardController::class)->only('store');
});

