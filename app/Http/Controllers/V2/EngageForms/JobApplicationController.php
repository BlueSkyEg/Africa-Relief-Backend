<?php

namespace App\Http\Controllers\V2\EngageForms;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V2\StoreJobApplicationRequest;
use App\Modules\EngageForms\JobApplication\Services\JobApplicationService;
use Illuminate\Http\JsonResponse;

class JobApplicationController extends Controller
{
    public function __construct(private readonly JobApplicationService $jobApplicationService)
    {
    }


    /**
     * @param StoreJobApplicationRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function storeJobApplication(StoreJobApplicationRequest $request): JsonResponse
    {
        $this->jobApplicationService->storeJobApplication($request->except('resume'), $request->file('resume'));

        return response()->success('Job application sent successfully.');
    }
}
