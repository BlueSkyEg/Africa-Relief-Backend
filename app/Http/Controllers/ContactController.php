<?php

namespace App\Http\Controllers;

use App\Modules\Contact\Requests\StoreContentRequest;
use App\Modules\Contact\Services\StoreContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(private readonly StoreContactService $storeContactService)
    {
    }

    public function storeContact(StoreContentRequest $request)
    {
        return $this->storeContactService->storeContact($request->validated());
    }
}
