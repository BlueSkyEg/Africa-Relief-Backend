<?php

namespace App\Modules\Stripe\Services;

use App\Http\Requests\Stripe\SingleCharge\CreateSingleChargeRequest;
use Stripe\StripeClient;

class StripePaymentService extends BaseStripeService
{
    public function __construct(StripeClient $stripe, private readonly StripeIntentService $stripeIntentService)
    {
        parent::__construct($stripe);
    }

    public function comfirmPayment(CreateSingleChargeRequest $request)
    {
        $donor = $this->getDonorService->getOrCreateDonor($request->name, $request->email, $request->paymentMethodId, $request->savePaymentMethod);
        $intentResult = $this->stripeIntentService->createPaymentIntent([
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'statement_descriptor' => 'AFRICA-RELIEF.ORG',
            'confirm' => true, // Confirm the payment automatically
            'amount' => $request->amount * 100, // The amount in cents
            'customer' => $donor->stripe_customer_id,
            'payment_method' => $request->paymentMethodId,
            'description' => $request->donationFormTitle,
            'metadata' => [
                'Donation Form Id' => $request->donationFormId,
            ]
        ]);
    }
}
