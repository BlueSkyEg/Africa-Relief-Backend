<?php

namespace App\Modules\Mobile\Services;

use App\Enums\CarouselTypeEnum;
use App\Enums\PostTypeEnum;
use App\Exceptions\ApiException;
use App\Modules\CarouselSlide\Resources\CarouselSlideResource;
use App\Modules\CarouselSlide\Services\GetCarouselSlideService;
use App\Modules\PostCore\Blog\Resources\BlogBriefResource;
use App\Modules\PostCore\Blog\Services\BlogService;
use App\Modules\PostCore\PostCategory\Resources\PostCategoryResource;
use App\Modules\PostCore\PostCategory\Services\PostCategoryService;
use App\Modules\PostCore\Project\Resources\ProjectBriefResource;
use App\Modules\PostCore\Project\Services\ProjectService;

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
        // $homeCarousel = $this->getCarouselSlideService->getCarousel(CarouselTypeEnum::Home_Carousel->value);
        $filtered_cat_arr = ['crisis', 'back-to-school'];
        $homeCarousel = $this->getProjectService->getAllProjectsByCategories($filtered_cat_arr, 10, true);

        $projectCategories = $this->getPostCategoryService->getPostCategories(PostTypeEnum::PROJECT);

        $latestBlogs = $this->getBlogService->getAllBlogs(null, 7, true);

        $latestProjects = $this->getProjectService->getAllLatestProjects(7, true);

        return [
            'home_carousel' => ProjectBriefResource::collection($homeCarousel),
            'project_categories' => PostCategoryResource::collection($projectCategories),
            'latest_blogs' => BlogBriefResource::collection($latestBlogs),
            'latest_projects' => ProjectBriefResource::collection($latestProjects)
        ];
    }
}
