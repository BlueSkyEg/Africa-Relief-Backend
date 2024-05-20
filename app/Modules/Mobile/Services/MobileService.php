<?php

namespace App\Modules\Mobile\Services;

use App\Enums\CarouselTypeEnum;
use App\Modules\CarouselSlide\Services\GetCarouselSlideService;
use App\Modules\Post\Blog\Services\GetBlogService;
use App\Modules\Post\PostCategory\Services\GetPostCategoryService;
use App\Modules\Post\Project\Services\GetProjectService;
use Illuminate\Http\JsonResponse;

class MobileService
{
    public function __construct(
        private readonly GetBlogService $getBlogService,
        private readonly GetProjectService $getProjectService,
        private readonly GetCarouselSlideService $getCarouselSlideService,
        private readonly GetPostCategoryService $getPostCategoryService,
    )
    {
    }

    public function getMobileHomeScreenData(): JsonResponse
    {
        try {
            $homeCarousel = $this->getCarouselSlideService->getCarousel(CarouselTypeEnum::Home_Carousel->value);

            $projectCategories = $this->getPostCategoryService->getProjectCategories();

            $latestBlogs = $this->getBlogService->getBlogs(null, 4, true);

            $latestProjects = $this->getProjectService->getProjects(null, 4, true);

            $data = [
                'home_carousel' => json_decode($homeCarousel->content())->data,
                'project_categories' => json_decode($projectCategories->content())->data,
                'latest_blogs' => json_decode($latestBlogs->content())->data->data,
                'latest_projects' => json_decode($latestProjects->content())->data->data
            ];

            return response()->api(true, 'mobile home screen data retrieved successfully', $data);
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
