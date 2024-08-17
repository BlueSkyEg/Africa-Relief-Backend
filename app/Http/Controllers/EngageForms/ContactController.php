<?php

namespace App\Http\Controllers\EngageForms;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Modules\EngageForms\Contact\Requests\StoreContentRequest;
use App\Modules\EngageForms\Contact\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(private readonly ContactService $contactService)
    {
    }


    /**
     * @param StoreContentRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function storeContact(StoreContentRequest $request): JsonResponse
    {
        $this->contactService->storeContact($request->validated());

        return response()->success('Contact sent successfully.');
    }
}
