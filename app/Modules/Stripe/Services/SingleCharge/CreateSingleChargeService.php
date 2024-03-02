<?php

namespace App\Modules\Stripe\Services\SingleCharge;

use App\Models\Donor;
use App\Http\Requests\Stripe\SingleCharge\CreateSingleChargeRequest;
use App\Modules\Donation\Repositories\DonationRepository;
use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class CreateSingleChargeService extends BaseStripeService
{
    public function create(CreateSingleChargeRequest $request): JsonResponse
    {
        $isUser = auth('sanctum')->check();
        if (isset($request->paymentMethodId) && !$isUser) {
            $donor = Donor::where('email', $request->email)->first();

            if (!$donor) {
                $stripeCustomer = $this->createCustomer($request->firstName .' '. $request->lastName, $request->email);

                $donor = Donor::create([
                    'email' => $request->email,
                    'stripe_customer_id' => $stripeCustomer->id
                ]);
            }

            return $this->createPaymentIntent($request, $donor);
        }

        return $this->confirmPaymentIntent($request);
    }

    private function createPaymentIntent(CreateSingleChargeRequest $request, Donor $donor): JsonResponse
    {
        try {
            $intent = $this->stripe->paymentIntents->create([
                'amount' => $request->amount * 100, // The amount in cents
                'currency' => 'USD',
                'customer' => $donor->stripe_customer_id,
                'payment_method' => $request->paymentMethodId,
                'description' => $request->paymentDescription,
                'payment_method_types' => ['card'],
                'confirm' => true,
                'statement_descriptor' => 'AFRICA-RELIEF.ORG',
                'expand' => ['customer', 'review', 'payment_method'],
                'metadata' => [
                    'Donor Comment' => $request->billingComment,
                    'Anonymous Donation' => $request->anonymousDonation,
                    'project_id' => $request->projectId,
                    'donor_id' => $donor->id
                ]
            ]);
            $donation = (new DonationRepository())->createDonation($intent, $donor, $request->donationFormId, $request->billingComment, $request->anonymousDonation);
            return $this->generateIntentResponse($intent);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    private function confirmPaymentIntent(CreateSingleChargeRequest $request): JsonResponse
    {
        try {
            $intent = $this->stripe->paymentIntents->retrieve($request->paymentIntentId)->confirm();

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

    private function createCustomer(string $name, string $email) {
        return $this->stripe->customers->create([
            'name' => $name,
            'email' => $email
        ]);
    }
}
