<?php

namespace App\Http\Controllers;

use App\Modules\NewsletterSubscriber\Requests\StoreNewsletterSubscriberRequest;
use App\Modules\NewsletterSubscriber\Requests\UpdateNewsletterSubscriberRequest;
use App\Modules\NewsletterSubscriber\Services\StoreNewsletterSubscriberService;
use App\Modules\NewsletterSubscriber\Services\UpdateNewsletterSubscriberService;
use Illuminate\Http\JsonResponse;

class NewsletterSubscriberController extends Controller
{
    public function __construct(
        private readonly StoreNewsletterSubscriberService $storeNewsletterSubscriberService,
        private readonly UpdateNewsletterSubscriberService $updateNewsletterSubscriberService
    )
    {
    }

    public function storeNewsletterSubscriber(StoreNewsletterSubscriberRequest $request): JsonResponse
    {
        return $this->storeNewsletterSubscriberService->storeNewsletterSubscriber($request->validated('email'));
    }

    public function unsubscribeNewsletterSubscriber(UpdateNewsletterSubscriberRequest $request): JsonResponse
    {
        return $this->updateNewsletterSubscriberService->unsubscribeNewsletterSubscriber($request->validated('email'));
    }
}
