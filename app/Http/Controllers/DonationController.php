<?php

namespace App\Http\Controllers;

use App\Modules\Donation\Services\GetDonationService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(private GetDonationService $getDonationService)
    {
    }

	public function getUserDonations()
	{
		return $this->getDonationService->getUserDonations();
//		return auth('sanctum')->user()->donations;
	}
}
