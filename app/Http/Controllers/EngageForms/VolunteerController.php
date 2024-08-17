<?php

namespace App\Http\Controllers\EngageForms;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Modules\EngageForms\Volunteer\Requests\StoreVolunteerRequest;
use App\Modules\EngageForms\Volunteer\Services\VolunteerService;
use Illuminate\Http\JsonResponse;

class VolunteerController extends Controller
{
    public function __construct(private readonly VolunteerService $volunteerService)
    {
    }


    /**
     * @param StoreVolunteerRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function storeVolunteer(StoreVolunteerRequest $request): JsonResponse
    {
        $this->volunteerService->storeVolunteer($request->validated());

        return response()->success(true, 'volunteer message sent successfully');
    }
}
