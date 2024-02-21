<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\SingleCharge\CreateSingleChargeRequest;
use App\Modules\Stripe\Services\SingleCharge\CreateSingleChargeService;
use Illuminate\Http\JsonResponse;

class SingleChargeController extends Controller
{
    public function createSingleCharge(CreateSingleChargeService $createSingleChargeService, CreateSingleChargeRequest $request): JsonResponse
    {
        return $createSingleChargeService->create($request);
    }
}
