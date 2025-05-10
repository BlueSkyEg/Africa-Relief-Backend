<?php

namespace App\Http\Controllers\V2\DonationCore;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\V2\DonationFormResource;
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
        // $donationFormId = 14577; // Equal Opportunity For Children Form
        $donationFormId = 27409; // General Fund Project (Where Most Needed)

        $donationForm = $this->getDonationFormService->getDonationFormById($donationFormId);

        return response()->success('Donation form retrieved successfully.', new DonationFormResource($donationForm));
    }

    public function getDonationForms(): JsonResponse
    {
        $donationForms = \App\Models\DonationForm::all();
        return response()->success('Donation form retrieved successfully.', $donationForms);
    }
}
