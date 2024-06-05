<?php

namespace App\Modules\NewsletterSubscriber\Services;

use App\Modules\NewsletterSubscriber\Emails\NewsletterSubscriberMail;
use App\Modules\NewsletterSubscriber\NewsletterSubscriberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class StoreNewsletterSubscriberService
{
    public function __construct(private readonly NewsletterSubscriberRepository $newsletterSubscriberRepository)
    {
    }

    public function storeNewsletterSubscriber(string $email): JsonResponse
    {
        try {
            $this->newsletterSubscriberRepository->storeNewsletterSubscriber($email);
            Mail::to($email)->send(new NewsletterSubscriberMail());

            return response()->api(true, 'user subscribed successfully');
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
