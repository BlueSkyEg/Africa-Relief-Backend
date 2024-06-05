<?php

namespace App\Modules\NewsletterSubscriber;

class NewsletterSubscriberRepository
{
    public function storeNewsletterSubscriber(string $email)
    {
        return NewsletterSubscriber::updateOrCreate(
            ['email' => $email],
            ['is_subscribed' => 1]
        );
    }

    public function unsubscribeNewsletterSubscriber(string $email)
    {
        return NewsletterSubscriber::where('email', $email)->update([
            'is_subscribed' => 0
        ]);
    }
}
