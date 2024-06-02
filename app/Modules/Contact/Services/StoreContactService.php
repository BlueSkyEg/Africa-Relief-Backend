<?php

namespace App\Modules\Contact\Services;

use App\Modules\Contact\Emails\ContactReceiverMail;
use App\Modules\Contact\Emails\ContactSenderMail;
use Illuminate\Support\Facades\Mail;

class StoreContactService
{
    public function __construct()
    {
    }

    public function storeContact(array $contact)
    {
        try {
            Mail::to($contact['email'])->send(new ContactSenderMail($contact['name']));
            Mail::to(env('CONTACT_RECEIVER_EMAIL'))->send(new ContactReceiverMail($contact));

            return response()->api(true, 'contact sent successfully');
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
