<?php

namespace App\Modules\Post\Career\Services;

use App\Exceptions\ApiException;
use App\Modules\Post\Career\Career;
use App\Modules\Post\Career\Repositories\CareerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CareerService
{
    public function __construct(private readonly CareerRepository $careerRepository)
    {
    }


    /**
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllCareers(int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return $this->careerRepository->getAll($perPage, $published);
    }


    /**
     * @param string $careerSlug
     * @param bool|null $published
     * @return Career
     * @throws ApiException
     */
    public function getCareer(string $careerSlug, bool|null $published = null): Career
    {
        $career = $this->careerRepository->getBySlug($careerSlug, $published);

        if (!$career) {
            throw new ApiException('Career not found.');
        }

        return $career;
    }
}
