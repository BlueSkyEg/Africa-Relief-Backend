<?php

use App\Http\Controllers\EngageForms\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::post('/volunteers/store', [VolunteerController::class, 'storeVolunteer']);
