<?php

use App\Http\Controllers\V2\EngageForms\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::post('/volunteers/store', [VolunteerController::class, 'storeVolunteer']);
