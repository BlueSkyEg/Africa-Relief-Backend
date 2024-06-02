<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('/contacts/store', [ContactController::class, 'storeContact']);
