<?php

namespace App\Http\Controllers\DonationCore;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Modules\DonationCore\DonationForm\Resources\DonationFormResource;
use App\Modules\DonationCore\DonationForm\Services\DonationFormService;
use Illuminate\Http\JsonResponse;

class DonationFormController extends Controller
{
    public function __construct(private readonly DonationFormService $getDonationFormService)
    {
    }


    /**
     * @return JsonResponse
     * @throws ApiException
     */
    public function getHomePageDonationForm(): JsonResponse
    {
        $donationFormId = 14577; // Equal Opportunity For Children Form

        $donationForm = $this->getDonationFormService->getDonationFormById($donationFormId);

        return response()->success('Donation form retrieved successfully.', new DonationFormResource($donationForm));
    }
}