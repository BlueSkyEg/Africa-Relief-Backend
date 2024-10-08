<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
	    VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $verifyParamsUri = Str::after($url, env('APP_URL') . '/api');
			$frontUrl = config('app.frontend_url')."/verify-email?verifyParamsUri=".$verifyParamsUri;

		    return (new MailMessage)
			    ->subject('Verify Email Address')
			    ->line('Click the button below to verify your email address.')
			    ->action('Verify Email Address', $frontUrl);
	    });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/reset-password?token=$token&email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
