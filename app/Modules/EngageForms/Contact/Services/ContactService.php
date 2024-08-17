<?php

namespace App\Modules\EngageForms\Contact\Services;

use App\Exceptions\ApiException;
use App\Modules\EngageForms\Contact\Emails\ContactReceiverMail;
use App\Modules\EngageForms\Contact\Emails\ContactSenderMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    public function __construct()
    {
    }

    /**
     * @param array $contact
     * @return void
     * @throws ApiException
     */
    public function storeContact(array $contact): void
    {
        try {
            Mail::to($contact['email'])->send(new ContactSenderMail($contact['name']));
            Mail::to(env('CONTACT_RECEIVER_EMAIL'))->send(new ContactReceiverMail($contact));

        } catch (\Exception $e) {
            Log::debug('An error occurred while create contact: ' . $e->getMessage());
            throw new ApiException('An error occurred while create contact.');
        }
    }
}
