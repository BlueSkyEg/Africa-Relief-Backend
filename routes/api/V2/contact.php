<?php

use App\Http\Controllers\V2\EngageForms\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('/contacts/store', [ContactController::class, 'storeContact']);
