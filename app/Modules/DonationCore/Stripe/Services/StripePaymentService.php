<?php

namespace App\Modules\DonationCore\Stripe\Services;

use App\Modules\DonationCore\Donation\Donation;
use App\Modules\DonationCore\Donation\Services\DonationService;
use App\Modules\DonationCore\Donor\Donor;
use App\Modules\DonationCore\Donor\Exceptions\DonorNotFoundException;
use App\Modules\DonationCore\Donor\Services\DonorService;
use App\Modules\DonationCore\Stripe\Exceptions\StripeApiException;
use App\Modules\DonationCore\Subscription\Services\SubscriptionService;
use App\Modules\DonationCore\Subscription\Subscription;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\StripeClient;

class StripePaymentService extends BaseStripeService
{
    public function __construct(
        StripeClient                                $stripe,
        private readonly DonorService               $donorService,
        private readonly DonationService            $donationService,
        private readonly SubscriptionService        $subscriptionService,
        private readonly StripePaymentMethodService $stripePaymentMethodService
    )
    {
        parent::__construct($stripe);
    }


    /**
     * Setup Intent
     * This method creates setup intent has client_secret
     * that clientside would use it to load the stripe card elements
     * add save new card
     *
     * @return \Stripe\SetupIntent
     * @throws StripeApiException
     */
    public function setupIntent(): \Stripe\SetupIntent
    {
        try {
            return $this->stripe->setupIntents->create([
                'payment_method_types' => ['card']
            ]);
        } catch (ApiErrorException $e) {
            throw new StripeApiException();
        }
    }


    /**
     * Create Stripe Payment
     * Check if payment is recurring payment
     * or one time payment
     *
     * @param array $attributes
     * @return \Stripe\PaymentIntent
     * @throws DonorNotFoundException
     * @throws StripeApiException
     */
    public function createStripePayment(array $attributes): \Stripe\PaymentIntent
    {
        if (isset($attributes['recurringPeriod'])) {
            return $this->createStripeSubscriptionPayment($attributes);
        }

        return $this->createStripeOneTimePayment($attributes);
    }


    /**
     * Confirm One Time Payment
     * This method create payment intent and confirm
     * the payment automatically after create the intent
     * then return the payment intent status
     *
     * @param array $attributes
     * @return \Stripe\PaymentIntent
     * @throws DonorNotFoundException
     * @throws StripeApiException
     */
    private function createStripeOneTimePayment(array $attributes): \Stripe\PaymentIntent
    {
        print_r($attributes);
        $donor = $this->donorService->getOrCreateDonor($attributes);
        if (!$donor) throw new DonorNotFoundException();

        DB::beginTransaction();

        // Calculate the amount including Stripe fees if 'coverFees' is checked
        $feeAmount = 0.0;  // Initialize the variable to avoid undefined variable error

        if (isset($attributes['coverFees']) && ($attributes['coverFees'] == true || $attributes['coverFees'] == 1)) {
            $feeAmount = round(($attributes['amount'] * 0.029 + 0.30), 2);
        }
        $attributes['amount'] += $feeAmount;

        $donation = $this->handleStripePayment($donor, $attributes);

        try {
            $paymentData = [
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'statement_descriptor' => 'AFRICA-RELIEF.ORG',
                'amount' => $attributes['amount'] * 100, // The amount in cents
                // 'amount' => $amount * 100, // The amount in cents
                'confirm' => true, // Confirm the payment automatically
                'customer' => $donor->stripe_customer_id,
                'payment_method' => $attributes['stripePaymentMethodId'] ?? $donation->paymentMethod->stripe_payment_method_id,
                'description' => $donation->donationForm->title,
                'metadata' => [
                    'Donation Post ID' => $donation->id
                ]
            ];
            $intent = $this->stripe->paymentIntents->create($paymentData);
        } catch (ApiErrorException $e) {
            DB::rollBack();
            throw new StripeApiException($e->getMessage());
        }

        $donation->stripe_transaction_id = $intent->id;
        $donation->completed_date = $intent->created;
        $donation->status = $intent->status;
        $donation->live_mode = $intent->livemode;
        $donation->save();

        DB::commit();

        return $intent;
    }


    /**
     * Create Recurring Payments
     * This method create subscription (Recurring Payment)
     *  with stripe and return the payment intent status
     *
     * @param array $attributes
     * @return \Stripe\PaymentIntent
     * @throws StripeApiException
     * @throws DonorNotFoundException
     */
    private function createStripeSubscriptionPayment(array $attributes): \Stripe\PaymentIntent
    {
        $donor = $this->donorService->getOrCreateDonor($attributes);
        if (!$donor) throw new DonorNotFoundException();

        DB::beginTransaction();

        // Calculate the amount including Stripe fees if 'coverFees' is checked
        $feeAmount = 0.0;  // Initialize the variable to avoid undefined variable error

        if (isset($attributes['coverFees']) && ($attributes['coverFees'] == true || $attributes['coverFees'] == 1)) {
            $feeAmount = round(($attributes['amount'] * 0.029 + 0.30), 2);
        }
        $attributes['amount'] += $feeAmount;

        $donation = $this->handleStripePayment($donor, $attributes);

        try {
            $price = $this->createProductPrice($subscription);
            $stripeSubscription = $this->createStripeSubscription($donor, $donation, $price, stripePaymentMethodId: $attributes['stripePaymentMethodId'] ?? null);
            $invoice = $this->addStatementDescriptorToInvoice($stripeSubscription);
            $intent = $this->stripe->paymentIntents->update(
                $invoice->payment_intent,
                ['metadata' => ['Donation Post ID' => $donation->id]]
            )->confirm();
        } catch (ApiErrorException $e) {
            DB::rollBack();
            throw new StripeApiException();
        }

        $donation->subscription_id = $subscription->id;
        $donation->stripe_transaction_id = $intent->id;
        $donation->completed_date = $intent->created;
        $donation->status = $intent->status;
        $donation->live_mode = $intent->livemode;
        $donation->save();

        $subscription->stripe_subscription_id = $stripeSubscription->id;
        $subscription->completed_date = $invoice->period_start;
        $subscription->expiration_date = $invoice->period_end;
        $subscription->status = $intent->status;
        $subscription->live_mode = $intent->livemode;
        $subscription->save();

        DB::commit();

        return $intent;
    }


    /**
     * Handle Stripe Payment
     * In this method we get or create the donor
     * then check if "savePaymentMethod" attribute is true to attach the payment method
     * to stripe customer then save it in database and get the payment_method_id
     * then create donation with the available data
     *
     * @param Donor $donor
     * @param array $attributes
     * @return Donation|array
     * @throws StripeApiException
     */
    private function handleStripePayment(Donor $donor, array $attributes): Donation|array
    {
        // Attach Payment Method To Stripe Customer
        if ($attributes['savePaymentMethod'] && isset($attributes['stripePaymentMethodId'])) {
            $this->stripePaymentMethodService->attachPaymentMethod($attributes['stripePaymentMethodId'], $donor);
        }

        $donationData = [
            'donor_id' => $donor->id,
            'amount' => $attributes['amount'],
            'currency' => 'usd',
            'donation_form_id' => $attributes['donationFormId'],
            'payment_method_id' => $attributes['paymentMethodId'] ?? null,
            'anonymous_donation' => $attributes['anonymousDonation'],
            'donor_ip' => $attributes['ip'],
            'billing_comment' => $attributes['billingComment'] ?? null
        ];
        $donation = $this->donationService->createDonation($donationData);

        if (isset($attributes['recurringPeriod'])) {
            $subscriptionData = [
                'donor_id' => $donor->id,
                'donation_form_id' => $donation->donation_form_id,
                'parent_donation_id' => $donation->id,
                'initial_amount' => $donation->amount,
                'recurring_amount' => $donation->amount,
                'period' => $attributes['recurringPeriod'],
            ];
            $subscription = $this->subscriptionService->createSubscription($subscriptionData);

            return [$donation, $subscription];
        }

        return $donation;
    }


    /**
     * Create Product Price
     * This method create plan on stripe
     * that customer can subscribe to it
     *
     * @param Subscription $subscription
     * @return \Stripe\Price
     * @throws ApiErrorException
     */
    private function createProductPrice(Subscription $subscription): \Stripe\Price
    {
        return $this->stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => $subscription->initial_amount * 100, // The amount in cents
            'recurring' => ['interval' => $subscription->period],
            'product_data' => ['name' => $subscription->donationForm->title],
        ]);
    }


    /**
     * Create Subscription
     * This method create subscription on stripe
     * for a specific plan and by default the subscription
     * status will be incomplete to handel requires_action (OTP) if exists
     *
     * @param Donor $donor
     * @param Donation $donation
     * @param Price $price
     * @param string $paymentMethodType
     * @return \Stripe\Subscription
     * @throws ApiErrorException
     */
    private function createStripeSubscription(Donor $donor, Donation $donation, \Stripe\Price $price, string $paymentMethodType = 'card', string $stripePaymentMethodId = null): \Stripe\Subscription
    {
        $stripeSubscriptionData = [
            'customer' => $donor->stripe_customer_id,
            'items' => [['price' => $price->id]],
            'expand' => ['latest_invoice.payment_intent.payment_method'],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
            'metadata' => [
                'Donation Post ID' => $donation->id,
            ]
        ];
        if ($paymentMethodType === 'card') $stripeSubscriptionData['default_payment_method'] = $stripePaymentMethodId ?? $donation->paymentMethod->stripe_payment_method_id;

        return $this->stripe->subscriptions->create($stripeSubscriptionData);
    }


    /**
     * Add Statement Descriptor To Invoice
     * This method add statement descriptor (AFRICA-RELIEF.ORG)
     * to can identify the payments that created by website
     *
     * @param \Stripe\Subscription $subscription
     * @return \Stripe\Invoice
     * @throws ApiErrorException
     */
    private function addStatementDescriptorToInvoice(\Stripe\Subscription $subscription): \Stripe\Invoice
    {
        return $this->stripe->invoices->update(
            $subscription->latest_invoice->id,
            ['statement_descriptor' => 'AFRICA-RELIEF.ORG']
        );
    }


    /**
     * Cancel Recurring Payment
     * This method cancel specific subscription
     * (Recurring Payment) on stripe by subscription id
     *
     * @param string $stripeSubscriptionId
     * @return \Stripe\Subscription
     * @throws StripeApiException
     */
    public function cancelStripeSubscription(string $stripeSubscriptionId): \Stripe\Subscription
    {
        try {
            $stripeSubscription = $this->stripe->subscriptions->cancel($stripeSubscriptionId);
        } catch (ApiErrorException $e) {
            throw new StripeApiException('An error occurred during cancel the subscription.');
        }

        return $stripeSubscription;
    }


    //--------------------------------
    // Stripe Express Checkout
    //--------------------------------

    /**
     * @param array $attributes
     * @return \Stripe\PaymentIntent
     * @throws DonorNotFoundException
     * @throws StripeApiException
     */
    public function createStripeExpressCheckout(array $attributes): \Stripe\PaymentIntent
    {
        if (isset($attributes['recurringPeriod'])) {
            return $this->createStripeExpressCheckoutForSubscription($attributes);
        }

        return $this->createStripeExpressCheckoutForOneTimePayment($attributes);
    }

    /**
     * @param array $attributes
     * @return \Stripe\PaymentIntent
     * @throws StripeApiException
     * @throws DonorNotFoundException
     */
    private function createStripeExpressCheckoutForOneTimePayment(array $attributes): \Stripe\PaymentIntent
    {
        $donor = $this->donorService->getOrCreateDonor($attributes);
        if (!$donor) throw new DonorNotFoundException();

        DB::beginTransaction();

        $donation = $this->handleStripeExpressCheckout($donor, $attributes);

        try {
            $paymentData = [
                'customer' => $donor->stripe_customer_id,
                'statement_descriptor' => 'AFRICA-RELIEF.ORG',
                'amount' => $attributes['amount'] * 100,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'description' => $donation->donationForm->title,
                'metadata' => [
                    'Donation Post ID' => $donation->id
                ]
            ];
            $intent = $this->stripe->paymentIntents->create($paymentData);
        } catch (ApiErrorException $e) {
            DB::rollBack();
            throw new StripeApiException($e->getMessage());
        }

        DB::commit();

        return $intent;
    }


    /**
     * @param array $attributes
     * @return \Stripe\PaymentIntent
     * @throws DonorNotFoundException
     * @throws StripeApiException
     */
    private function createStripeExpressCheckoutForSubscription(array $attributes): \Stripe\PaymentIntent
    {
        $donor = $this->donorService->getOrCreateDonor($attributes);
        if (!$donor) throw new DonorNotFoundException();

        DB::beginTransaction();

        [$donation, $subscription] = $this->handleStripeExpressCheckout($donor, $attributes);

        try {
            $price = $this->createProductPrice($subscription);
            $stripeSubscription = $this->createStripeSubscription($donor, $donation, $price, paymentMethodType: 'wallet');
            $this->addStatementDescriptorToInvoice($stripeSubscription);
            $intentId = $stripeSubscription->latest_invoice->payment_intent->id;
            $intent = $this->stripe->paymentIntents->update(
                $intentId,
                ['metadata' => ['Donation Post ID' => $donation->id]]
            );
        } catch (ApiErrorException $e) {
            DB::rollBack();
            throw new StripeApiException($e->getMessage());
        }

        $donation->subscription_id = $subscription->id;
        $donation->save();

        $subscription->stripe_subscription_id = $stripeSubscription->id;
        $subscription->save();

        DB::commit();

        return $intent;
    }


    /**
     * @param Donor $donor
     * @param array $attributes
     * @return Donation|array
     */
    private function handleStripeExpressCheckout(Donor $donor, array $attributes): Donation|array
    {
        $donationData = [
            'donor_id' => $donor->id,
            'amount' => $attributes['amount'],
            'currency' => 'usd',
            'donation_form_id' => $attributes['donationFormId'],
            'donor_ip' => $attributes['ip']
        ];
        $donation = $this->donationService->createDonation($donationData);

        if (isset($attributes['recurringPeriod'])) {
            $subscriptionData = [
                'donor_id' => $donor->id,
                'donation_form_id' => $donation->donation_form_id,
                'parent_donation_id' => $donation->id,
                'initial_amount' => $donation->amount,
                'recurring_amount' => $donation->amount,
                'period' => $attributes['recurringPeriod'],
            ];
            $subscription = $this->subscriptionService->createSubscription($subscriptionData);

            return [$donation, $subscription];
        }

        return $donation;
    }
}
