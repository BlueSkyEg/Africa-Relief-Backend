<?php

namespace App\Modules\EngageForms\NewsletterSubscriber\Repositories;

use App\Models\NewsletterSubscriber;

class NewsletterSubscriberRepository
{
    /**
     * @param string $email
     * @return NewsletterSubscriber
     */
    public function subscribe(string $email): NewsletterSubscriber
    {
        return NewsletterSubscriber::updateOrCreate(
            ['email' => $email],
            ['is_subscribed' => 1]
        );
    }


    /**
     * @param string $email
     */
    public function unsubscribe(string $email)
    {
        return NewsletterSubscriber::where('email', $email)->update([
            'is_subscribed' => 0
        ]);
    }
}
