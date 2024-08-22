<?php

namespace App\Modules\EngageForms\JobApplication\Services;

use App\Exceptions\ApiException;
use App\Modules\EngageForms\JobApplication\Emails\JobApplicantMail;
use App\Modules\EngageForms\JobApplication\Emails\JobApplicationReceiverMail;
use App\Modules\PostCore\Career\Services\CareerService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class JobApplicationService
{
    public function __construct(private readonly CareerService $getCareerService)
    {
    }


    /**
     * @param array $application
     * @param UploadedFile $resume
     * @return void
     * @throws ApiException
     */
    public function storeJobApplication(array $application, UploadedFile $resume): void
    {
        try {
            $career = $this->getCareerService->getCareer($application['careerSlug']);

            Mail::to(env('JOB_APPLICATION_RECEIVER_EMAIL'))->send(new JobApplicationReceiverMail($application, $resume, $career));
            Mail::to($application['email'])->send(new JobApplicantMail($application['name']));

        } catch (\Exception $e) {
            Log::debug('An error occurred while Storing new Job Application: ' . $e->getMessage());
            throw new ApiException('An error occurred while Storing new Job Application.');
        }
    }
}
