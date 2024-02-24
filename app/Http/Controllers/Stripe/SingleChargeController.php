<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\SingleCharge\CreateSingleChargeRequest;
use App\Services\Stripe\SingleCharge\CreateSingleChargeService;
use Illuminate\Http\JsonResponse;

class SingleChargeController extends Controller
{
    public function createSingleCharge(CreateSingleChargeService $createSingleChargeService, CreateSingleChargeRequest $request): JsonResponse
    {
        return $createSingleChargeService->create($request);
    }
}
