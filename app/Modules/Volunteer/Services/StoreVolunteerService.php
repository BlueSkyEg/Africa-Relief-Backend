<?php

namespace App\Modules\Volunteer\Services;

use App\Modules\Volunteer\Emails\VolunteerApplicantMail;
use App\Modules\Volunteer\Emails\VolunteerReceiverMail;
use Illuminate\Support\Facades\Mail;

class StoreVolunteerService
{
    public function __construct()
    {
    }

    public function storeVolunteer(array $volunteer)
    {
        try {
            Mail::to($volunteer['email'])->send(new VolunteerApplicantMail($volunteer['name']));
            Mail::to(env('VOLUNTEER_RECEIVER_EMAIL'))->send(new VolunteerReceiverMail($volunteer));

            return response()->api(true, 'volunteer message sent successfully');
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
