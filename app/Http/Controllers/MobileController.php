<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Modules\Mobile\Services\MobileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function __construct(private readonly MobileService $mobileService)
    {
    }


    /**
     * @return JsonResponse
     * @throws ApiException
     */
    public function getMobileHomeScreenData(): JsonResponse
    {
        $data = $this->mobileService->getMobileHomeScreenData();

        return response()->success('Mobile home screen data retrieved successfully', $data);
    }
}
