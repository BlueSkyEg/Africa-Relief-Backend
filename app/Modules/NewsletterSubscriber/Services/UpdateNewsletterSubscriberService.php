<?php

namespace App\Modules\NewsletterSubscriber\Services;

use App\Modules\NewsletterSubscriber\NewsletterSubscriberRepository;
use Illuminate\Http\JsonResponse;

class UpdateNewsletterSubscriberService
{
    public function __construct(private readonly NewsletterSubscriberRepository $newsletterSubscriberRepository)
    {
    }

    public function unsubscribeNewsletterSubscriber(string $email): JsonResponse
    {
        try {
            $this->newsletterSubscriberRepository->unsubscribeNewsletterSubscriber($email);

            return response()->api(true, 'user unsubscribed successfully');
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
