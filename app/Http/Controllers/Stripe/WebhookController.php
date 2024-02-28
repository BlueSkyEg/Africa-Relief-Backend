<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class WebhookController extends Controller
{
    public function __invoke()
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));

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
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // ... handle other event types
                $appendVar = fopen(public_path('text.json'), 'ab');
                fwrite($appendVar, $paymentIntent);
                fclose($appendVar);
            default:
                // $appendVar = fopen(public_path('text.json'), 'ab');
                // fwrite($appendVar, $event->data->object);
                // fclose($appendVar);
        }

        http_response_code(200);
    }
}
