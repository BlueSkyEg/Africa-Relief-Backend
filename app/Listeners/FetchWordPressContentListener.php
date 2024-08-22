<?php

namespace App\Listeners;

use App\Enums\PostTypeEnum;
use App\Modules\PostCore\Blog\Services\BlogService;
use App\Modules\PostCore\Career\Services\CareerService;
use App\Modules\PostCore\PostCategory\Services\PostCategoryService;
use App\Modules\PostCore\Project\Services\ProjectService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FetchWordPressContentListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly BlogService $blogService,
        private readonly PostCategoryService $postCategoryService,
        private readonly CareerService $careerService
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @throws \JsonException
     */
    public function handle(): void
    {
        // Project Categories
        $this->postCategoryService->createPostCategoriesFromJsonFile(PostTypeEnum::PROJECT, 'project_categories.json');

        // Projects
        $this->projectService->createProjectsFromJsonFile();

        // Blog Categories
        $this->postCategoryService->createPostCategoriesFromJsonFile(PostTypeEnum::BLOG, 'blog_categories.json');

        // Blogs
        $this->blogService->createBlogsFromJsonFile();

        // Careers
        $this->careerService->createCareersFromJsonFile();
    }
}
