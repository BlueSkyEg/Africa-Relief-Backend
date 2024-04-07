<?php

namespace App\Http\Controllers;

use App\Modules\Donation\Services\GetDonationService;

class DonationController extends Controller
{
    public function __construct(private readonly GetDonationService $getDonationService)
    {
    }

	public function getUserDonations()
	{
		return $this->getDonationService->getUserDonations();
	}
}
