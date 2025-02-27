<?php

use App\Http\Controllers\V1\EngageForms\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::post('/volunteers/store', [VolunteerController::class, 'storeVolunteer']);
