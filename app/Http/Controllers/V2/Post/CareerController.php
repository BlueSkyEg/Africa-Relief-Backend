<?php

namespace App\Http\Controllers\V2\Post;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\V2\CareerBriefResource;
use App\Http\Resources\V2\CareerResource;
use App\Modules\PostCore\Career\Services\CareerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function __construct(private readonly CareerService $careerService)
    {
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPublishedCareers(Request $request): JsonResponse
    {
        $careers = $this->careerService->getAllCareers(
            $request->query('perPage') ?: env('DEFAULT_PAGINATION_PER_PAGE'),
            true
        );

        return response()->pagination('Careers retrieved successfully.', CareerBriefResource::collection($careers), $careers);
    }


    /**
     * @param string $careerSlug
     * @return JsonResponse
     * @throws ApiException
     */
    public function getPublishedCareer(string $careerSlug): JsonResponse
    {
        $career = $this->careerService->getCareer($careerSlug, true);

        return response()->success('Career retrieved successfully.', new CareerResource($career));
    }
}
