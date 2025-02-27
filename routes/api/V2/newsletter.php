<?php

use App\Http\Controllers\V2\EngageForms\NewsletterSubscriberController;
use Illuminate\Support\Facades\Route;

Route::controller(NewsletterSubscriberController::class)->prefix('newsletter')->group(function () {
    Route::post('/subscribe', 'subscribeToNewsletter');
    Route::post('/unsubscribe', 'unsubscribeFromNewsletter');
});
