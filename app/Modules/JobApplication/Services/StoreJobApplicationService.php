<?php

namespace App\Modules\JobApplication\Services;

use App\Modules\JobApplication\Emails\JobApplicantMail;
use App\Modules\JobApplication\Emails\JobApplicationReceiverMail;
use App\Modules\Post\Career\Services\GetCareerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;

class StoreJobApplicationService
{
    public function __construct(private readonly GetCareerService $getCareerService)
    {
    }

    public function storeJobApplication(array $application, UploadedFile $resume): JsonResponse
    {
        try {
            $career = json_decode($this->getCareerService->getCareer($application['careerSlug'])->content())->data;

            Mail::to(env('JOB_APPLICATION_RECEIVER_EMAIL'))->send(new JobApplicationReceiverMail($application, $resume, $career));
            Mail::to($application['email'])->send(new JobApplicantMail($application['name']));

            return response()->api(true, 'job application sent successfully');
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
