<?php

namespace App\Http\Controllers\V2\EngageForms;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V2\StoreNewsletterSubscriberRequest;
use App\Http\Requests\V2\UpdateNewsletterSubscriberRequest;
use App\Modules\EngageForms\NewsletterSubscriber\Services\NewsletterSubscriberService;
use Illuminate\Http\JsonResponse;

class NewsletterSubscriberController extends Controller
{
    public function __construct(private readonly NewsletterSubscriberService $newsletterSubscriberService)
    {
    }


    /**
     * @param StoreNewsletterSubscriberRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function subscribeToNewsletter(StoreNewsletterSubscriberRequest $request): JsonResponse
    {
        $this->newsletterSubscriberService->subscribeToNewsletter($request->validated('email'));

        return response()->success('User subscribed successfully.');
    }


    /**
     * @param UpdateNewsletterSubscriberRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function unsubscribeFromNewsletter(UpdateNewsletterSubscriberRequest $request): JsonResponse
    {
        $this->newsletterSubscriberService->unsubscribeFromNewsletter($request->validated('email'));

        return response()->success('User unsubscribed successfully.');
    }
}
