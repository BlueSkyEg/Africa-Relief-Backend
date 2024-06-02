<?php

use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::post('job-applications/store', [JobApplicationController::class, 'storeJobApplication']);
