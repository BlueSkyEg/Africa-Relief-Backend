<?php

namespace App\Modules\Authentication\Services;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationService
{
    // Send a new email verification notification.
    public function resendEmailVerificationNotification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->api(false, 'email already verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->api(true, 'email verification link sent successfully');
    }

    // Mark the authenticated user's email address as verified.
    public function verifyEmail(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url').'/home?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url').'/home?verified=1'
        );
    }
}
