<?php

namespace App\Http\Controllers;

use App\Modules\Mobile\Services\MobileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function __construct(private readonly MobileService $mobileService)
    {
    }

    public function getMobileHomeScreenData(): JsonResponse
    {
        return $this->mobileService->getMobileHomeScreenData();
    }
}
