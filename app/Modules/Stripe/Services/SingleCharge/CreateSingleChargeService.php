<?php

namespace App\Modules\Stripe\Services\SingleCharge;

use App\Models\Donor;
use App\Http\Requests\Stripe\SingleCharge\CreateSingleChargeRequest;
use App\Modules\Donation\Services\CreateDonationService;
use App\Modules\Donation\Services\UpdateDonationService;
use App\Modules\Donor\Services\GetDonorService;
use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class CreateSingleChargeService extends BaseStripeService
{
	public function __construct(protected StripeClient $stripe, public GetDonorService $getDonorService, public CreateDonationService $createDonationService, public UpdateDonationService $updateDonationService)
	{
		parent::__construct($stripe);
	}

	public function create(CreateSingleChargeRequest $request): JsonResponse
	{
		if (isset($request->paymentMethodId)) {
			$donor = $this->getDonorService->getOrCreateDonor($request->name, $request->email, $request->paymentMethodId, $request->savePaymentMethod);

			return $this->createPaymentIntent($request, $donor);
		}

		return $this->confirmPaymentIntent($request);
	}

	private function createPaymentIntent(CreateSingleChargeRequest $request, Donor $donor): JsonResponse
	{
		try {
			$intent = $this->stripe->paymentIntents->create([
				'amount' => $request->amount * 100, // The amount in cents
				'currency' => 'usd',
				'customer' => $donor->stripe_customer_id,
				'payment_method' => $request->paymentMethodId,
				'description' => $request->paymentDescription,
				'payment_method_types' => ['card'],
				'confirm' => true,
				'confirmation_method' => 'manual',
				'statement_descriptor' => 'AFRICA-RELIEF.ORG',
				'expand' => ['customer', 'review', 'payment_method'],
			]);

			$this->createDonationService->createDonation($intent, $donor, $request->donationFormId, $request->billingComment, $request->anonymousDonation);

			return $this->generateIntentResponse($intent);
		} catch (ApiErrorException $e) {
			return response()->api(false, $e->getMessage());
		}
	}

	private function confirmPaymentIntent(CreateSingleChargeRequest $request): JsonResponse
	{
		try {
			$intent = $this->stripe->paymentIntents->retrieve($request->paymentIntentId)->confirm();

			$this->updateDonationService->updateDonationStatus($intent->id, $intent->status);

			return response()->api(true, 'payment created successfully', $intent);
		} catch (ApiErrorException $e) {
			return response()->api(false, $e->getMessage());
		}
	}

	private function generateIntentResponse(PaymentIntent $intent): JsonResponse
	{
		if ($intent->status === 'succeeded') {
			return response()->api(true, 'payment created successfully', $intent);
		}

		if ($intent->status === 'requires_action') {
			return response()->api(false, 'payment method requires action', [
				'requiresAction' => true,
				'clientSecret' => $intent->client_secret,
			]);
		}

		return response()->api(false, 'Invalid Payment Intent');
	}
}
