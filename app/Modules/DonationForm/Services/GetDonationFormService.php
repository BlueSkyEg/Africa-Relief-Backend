<?php

namespace App\Modules\DonationForm\Services;

use App\Modules\DonationForm\Repositories\DonationFormRepository;
use App\Modules\DonationForm\Resources\DonationFormResource;
use Illuminate\Http\JsonResponse;

class GetDonationFormService
{
    public function __construct(private readonly DonationFormRepository $donationFormRepository)
    {
    }

    public function getDonationFormById(int $formId): JsonResponse
    {
        $donationForm = $this->donationFormRepository->getDonationFormById($formId);
        return response()->api(true, 'donation form retrieved successfully', new DonationFormResource($donationForm));
    }
}
