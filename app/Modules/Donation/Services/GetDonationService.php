<?php

namespace App\Modules\Donation\Services;

use App\Modules\Donation\Repositories\DonationRepository;
use App\Modules\User\Services\GetUserService;

class GetDonationService
{
    public function __construct(private DonationRepository $donationRepository, private GetUserService $getUserService)
    {
    }

    public function getDonationById(int $donationId)
    {
        return $this->donationRepository->getDonationById($donationId);
    }

	public function getUserDonations()
	{
		$user = $this->getUserService->getAuthUser();
		$donations = $this->donationRepository->getUserDonations($user);

		return response()->api(true, 'donations retrieved successfully', $donations);
	}
}
