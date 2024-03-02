<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\StripeClient;

class WebhookController extends Controller
{
    public function __invoke()
    {

//        $stripe = new StripeClient(env('STRIPE_SECRET'));

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'charge.succeeded':
                $paymentIntent = $event->data->object;
                // ... handle other event types
                try {
                    Donation::create([
                        'donor_id' => $paymentIntent->metadata->donor_id,
//                    'subscription_id' => $paymentIntent->object,
                        'stripe_source_id' => $paymentIntent->payment_method,
                        'stripe_transaction_id' => $paymentIntent->payment_intent,
                        'payment_total' => $paymentIntent->amount / 100, // This amount in dollar
                        'project_id' => $paymentIntent->metadata->project_id,
                        'donor_billing_first_name' => $paymentIntent->metadata->first_name,
                        'donor_billing_last_name' => $paymentIntent->metadata->last_name,
                        'donor_billing_comment' => $paymentIntent->metadata->comment,
                        'anonymous_donation' => $paymentIntent->metadata->anonymous_donation,
                        'donor_billing_phone' => $paymentIntent->billing_details->phone,
                        'donor_billing_country' => $paymentIntent->billing_details->address->country,
                        'donor_billing_city' => $paymentIntent->billing_details->address->city,
                        'donor_billing_state' => $paymentIntent->billing_details->address->state,
                        'donor_billing_address_1' => $paymentIntent->billing_details->address->line1,
                        'donor_billing_address_2' => $paymentIntent->billing_details->address->line2,
                        'donor_billing_zip' => $paymentIntent->billing_details->address->postal_code,
                        'payment_mode' => $paymentIntent->livemode ? 'live' : 'test',
                        'completed_date' => Carbon::createFromTimestamp($paymentIntent->created)->format('Y-m-d H:i:s'),
                        'payment_currency' => $paymentIntent->currency,
//                        'payment_donor_ip' => $paymentIntent->review['ip_address']
                    ]);
                } catch (\Exception $e) {
                    $appendVar = fopen(public_path('text.json'), 'ab');
                    fwrite($appendVar, $e->getMessage());
                    fclose($appendVar);
                }

//             $appendVar = fopen(public_path('text.json'), 'ab');
//             fwrite($appendVar, $paymentIntent);
//             fclose($appendVar);
            default:
                // $appendVar = fopen(public_path('text.json'), 'ab');
                // fwrite($appendVar, $event->data->object);
                // fclose($appendVar);
        }

        http_response_code(200);
    }
}
