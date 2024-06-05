<?php

use App\Http\Controllers\NewsletterSubscriberController;
use Illuminate\Support\Facades\Route;

Route::post('/newsletter/subscribe', [NewsletterSubscriberController::class, 'storeNewsletterSubscriber']);
Route::post('/newsletter/unsubscribe', [NewsletterSubscriberController::class, 'unsubscribeNewsletterSubscriber']);
