<?php

namespace App\Modules\Mobile\Services;

use App\Enums\CarouselTypeEnum;
use App\Exceptions\ApiException;
use App\Modules\CarouselSlide\Resources\CarouselSlideResource;
use App\Modules\CarouselSlide\Services\GetCarouselSlideService;
use App\Modules\Post\Blog\Resources\BlogBriefResource;
use App\Modules\Post\Blog\Services\BlogService;
use App\Modules\Post\PostCategory\Resources\PostCategoryResource;
use App\Modules\Post\PostCategory\Services\PostCategoryService;
use App\Modules\Post\Project\Resources\ProjectBriefResource;
use App\Modules\Post\Project\Services\ProjectService;

class MobileService
{
    public function __construct(
        private readonly BlogService             $getBlogService,
        private readonly ProjectService          $getProjectService,
        private readonly GetCarouselSlideService $getCarouselSlideService,
        private readonly PostCategoryService     $getPostCategoryService,
    )
    {
    }


    /**
     * @return array
     * @throws ApiException
     */
    public function getMobileHomeScreenData(): array
    {
        $homeCarousel = $this->getCarouselSlideService->getCarousel(CarouselTypeEnum::Home_Carousel->value);

        $projectCategories = $this->getPostCategoryService->getProjectCategories();

        $latestBlogs = $this->getBlogService->getAllBlogs(null, 4, true);

        $latestProjects = $this->getProjectService->getAllProjects(null, 4, true);

        return [
            'home_carousel' => CarouselSlideResource::collection($homeCarousel),
            'project_categories' => PostCategoryResource::collection($projectCategories),
            'latest_blogs' => BlogBriefResource::collection($latestBlogs),
            'latest_projects' => ProjectBriefResource::collection($latestProjects)
        ];
    }
}
