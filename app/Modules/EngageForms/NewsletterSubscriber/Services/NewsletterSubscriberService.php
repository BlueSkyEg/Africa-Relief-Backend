<?php

namespace App\Modules\EngageForms\NewsletterSubscriber\Services;

use App\Exceptions\ApiException;
use App\Modules\EngageForms\NewsletterSubscriber\Emails\NewsletterSubscriberMail;
use App\Modules\EngageForms\NewsletterSubscriber\Repositories\NewsletterSubscriberRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterSubscriberService
{
    public function __construct(private readonly NewsletterSubscriberRepository $newsletterSubscriberRepository)
    {
    }


    /**
     * @param string $email
     * @return void
     * @throws ApiException
     */
    public function subscribeToNewsletter(string $email): void
    {
        try {
            $this->newsletterSubscriberRepository->subscribe($email);
            Mail::to($email)->send(new NewsletterSubscriberMail());

        } catch (\Exception $e) {
            Log::debug('An error occurred while subscribing to newsletter: ' . $e->getMessage());
            throw new ApiException('An error occurred while subscribing to newsletter.');
        }
    }


    /**
     * @param string $email
     * @return void
     * @throws ApiException
     */
    public function unsubscribeFromNewsletter(string $email): void
    {
        try {
            $this->newsletterSubscriberRepository->unsubscribe($email);

        } catch (\Exception $e) {
            Log::debug('An error occurred while unsubscribing from newsletter: ' . $e->getMessage());
            throw new ApiException('An error occurred while unsubscribing from newsletter.');
        }
    }
}
