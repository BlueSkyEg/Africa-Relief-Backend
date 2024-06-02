<?php

namespace App\Http\Controllers;

use App\Modules\JobApplication\Requests\StoreJobApplicationRequest;
use App\Modules\JobApplication\Services\StoreJobApplicationService;
use Illuminate\Http\JsonResponse;

class JobApplicationController extends Controller
{
    public function __construct(private readonly StoreJobApplicationService $storeJobApplicationService)
    {
    }

    public function storeJobApplication(StoreJobApplicationRequest $request): JsonResponse
    {
        return $this->storeJobApplicationService->storeJobApplication($request->except('resume'), $request->file('resume'));
    }
}
