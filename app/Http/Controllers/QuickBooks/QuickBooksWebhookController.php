<?php

namespace App\Http\Controllers\QuickBooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QuickBooksWebhookController extends Controller
{
    public function listenQuickbooksWebhook(Request $request): JsonResponse
    {
        // Your verifier token provided during webhook setup
        $verifierToken = config('quickbooks.webhooks_verify_token');

        // Validate the request
//        $validator = Validator::make($request->all(), [
//            'payload' => 'required',
//            'intuit-signature' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            // Invalid request, return error response
//            Log::error('Webhook validation error');
//            return response()->json(['error' => 'Invalid request'], 400);
//        }

        // Get the payload and signature from the request
        $payload = $request->getContent();
        Log::info('Payload: ' . $payload);
        $signature = $request->header('intuit-signature');
        Log::info('Signature: ' . $signature);

        // Hash payload using HMAC_SHA256 algorithm and the verifier token
        $hashedPayload = hash_hmac('sha256', $payload, $verifierToken);
        Log::info('Hash Payload: ' . $hashedPayload);

        // Convert the generated signature to base-16
        $convertedSignature = bin2hex($signature);
        Log::info('Converted Signature: ' . $convertedSignature);

        // Compare the generated signature with the signature from the request
        if ($hashedPayload !== $convertedSignature) {
            // Signatures do not match, reject the request
            Log::error('Webhook validation failed: Signatures do not match');
            return response()->json(['error' => 'Webhook validation failed'], 400);
        }

        // Signature validation passed, proceed to handle the webhook event
        $event = json_decode($payload, true);
        Log::info($event);

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }
}
