<?php

namespace App\Http\Controllers;

use App\Modules\DonationForm\Services\GetDonationFormService;
use Illuminate\Http\JsonResponse;

class DonationFormController extends Controller
{
    public function __construct(private readonly GetDonationFormService $getDonationFormService)
    {
    }

    public function getHomePageDonationForm(): JsonResponse
    {
        $donationFormId = 14577; // Equal Opportunity For Children Form

        return $this->getDonationFormService->getDonationFormById($donationFormId);
    }
}
