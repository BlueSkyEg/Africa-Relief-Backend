<?php

use App\Http\Controllers\V1\EngageForms\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::post('job-applications/store', [JobApplicationController::class, 'storeJobApplication']);
