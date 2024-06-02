<?php

use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::post('/volunteers/store', [VolunteerController::class, 'storeVolunteer']);
