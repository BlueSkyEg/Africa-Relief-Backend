<?php

namespace App\Modules\PostCore\Career\Services;

use App\Exceptions\ApiException;
use App\Models\Career;
use App\Modules\PostCore\Career\Repositories\CareerRepository;
use App\Modules\PostCore\Post\Services\PostService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CareerService
{
    public function __construct(
        private readonly CareerRepository $careerRepository,
        private readonly PostService      $postService
    )
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


    /**
     * @return void
     * @throws \JsonException
     */
    public function createCareersFromJsonFile(): void
    {
        $careers = json_decode(file_get_contents(public_path('db/careers.json')), true, 512, JSON_THROW_ON_ERROR);

        foreach ($careers as $career) {
            $postData = [
                'title' => $career['title'],
                'excerpt' => $career['excerpt'],
                'published' => 1,
                'meta_title' => $career['meta_title'],
                'meta_keywords' => $career['meta_keywords'],
                'meta_description' => $career['meta_description'],
                'meta_robots' => $career['meta_robots'],
                'meta_og_type' => $career['meta_og_type']
            ];
            $post = $this->postService->createPost($postData);

            $post->career()->create(['slug' => $career['slug']]);

            $post->contents()->createMany($career['contents']);
        }
    }
}
