<?php

namespace App\Http\Controllers;

use App\Modules\Volunteer\Requests\StoreVolunteerRequest;
use App\Modules\Volunteer\Services\StoreVolunteerService;

class VolunteerController extends Controller
{
    public function __construct(private readonly StoreVolunteerService $storeVolunteerService)
    {
    }

    public function storeVolunteer(StoreVolunteerRequest $request)
    {
        return $this->storeVolunteerService->storeVolunteer($request->validated());
    }
}
