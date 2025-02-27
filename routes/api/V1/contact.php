<?php

use App\Http\Controllers\V1\EngageForms\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('/contacts/store', [ContactController::class, 'storeContact']);
