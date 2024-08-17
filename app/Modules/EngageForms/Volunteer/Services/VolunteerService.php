<?php

namespace App\Modules\EngageForms\Volunteer\Services;

use App\Exceptions\ApiException;
use App\Modules\EngageForms\Volunteer\Emails\VolunteerApplicantMail;
use App\Modules\EngageForms\Volunteer\Emails\VolunteerReceiverMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VolunteerService
{
    public function __construct()
    {
    }


    /**
     * @param array $volunteer
     * @return void
     * @throws ApiException
     */
    public function storeVolunteer(array $volunteer): void
    {
        try {
            Mail::to($volunteer['email'])->send(new VolunteerApplicantMail($volunteer['name']));
            Mail::to(env('VOLUNTEER_RECEIVER_EMAIL'))->send(new VolunteerReceiverMail($volunteer));

        } catch (\Exception $e) {
            Log::debug('An error occurred while storing new volunteer: ' . $e->getMessage());
            throw new ApiException('An error occurred while storing new volunteer.');
        }
    }
}
