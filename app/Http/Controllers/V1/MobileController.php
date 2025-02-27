<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Modules\Mobile\Services\MobileService;
use Illuminate\Http\JsonResponse;

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
