<?php

namespace App\Modules\PostCore\Project\Services;

use App\Enums\PostTypeEnum;
use App\Exceptions\ApiException;
use App\Modules\Image\Services\ImageService;
use App\Modules\PostCore\Post\Services\PostService;
use App\Modules\PostCore\PostCategory\Services\PostCategoryService;
use App\Modules\PostCore\Project\Project;
use App\Modules\PostCore\Project\Repositories\ProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository   $projectRepository,
        private readonly PostService         $postService,
        private readonly ImageService        $imageService,
        private readonly PostCategoryService $postCategoryService
    )
    {
    }


    /**
     * @param string|null $categorySlug
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllProjects(string|null $categorySlug, int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return $this->projectRepository->getAll($categorySlug, $perPage, $published);
    }

    /**
     * @param string|null $categorySlug
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllLatestProjects(int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return $this->projectRepository->getAllLatest($perPage, $published);
    }

        /**
     * @param array $categorySlugs
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllProjectsByCategories(array $categorySlugs, int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return $this->projectRepository->getAllByCategories($categorySlugs, $perPage, $published);
    }



    /**
     * @param string $projectSlug
     * @param bool|null $published
     * @return Project
     * @throws ApiException
     */
    public function getProject(string $projectSlug, bool $published = null): Project
    {
        $project = $this->projectRepository->getBySlug($projectSlug, $published);

        if (!$project) {
            throw new ApiException('Project not found.');
        }

        return $project;
    }


    /**
     * @param string $projectSlug
     * @return Collection
     * @throws ApiException
     */
    public function getRelatedProjects(string $projectSlug): Collection
    {
        $currentProject = $this->projectRepository->getBySlug($projectSlug, true);

        if (!$currentProject) {
            throw new ApiException('Project not found.');
        }

        return $this->projectRepository->getRelated($currentProject);
    }


    /**
     * @param string $searchTerm
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchProjects(string $searchTerm, int $perPage): LengthAwarePaginator
    {
        return $this->projectRepository->search($searchTerm, $perPage);
    }


    /**
     * Create Projects From JSON File
     * This method creates the WordPress projects (Old Projects) that
     * had been created in JSON format you can find in this path "public/db"
     *
     * @return void
     * @throws \JsonException
     */
    public function createProjectsFromJsonFile(): void
    {
        $projects = json_decode(file_get_contents(public_path('db/projects.json')), true, 512, JSON_THROW_ON_ERROR);

        foreach (array_reverse($projects) as $project) {
            // Save project featured image in file storage and database
            $featuredImagePath = $this->imageService->saveImageByUrl($project['featured_image']['src']);
            $featuredImageData = [
                'src' => $featuredImagePath,
                'alt_text' => $project['featured_image']['alt_text']
            ];
            $featuredImage = $this->imageService->createImage($featuredImageData);

            // Create post for project
            $postData = [
                'title' => $project['title'],
                'excerpt' => $project['excerpt'],
                'published' => 1,
                'meta_title' => $project['meta_title'],
                'meta_keywords' => $project['meta_keywords'],
                'meta_description' => $project['meta_description'],
                'meta_robots' => $project['meta_robots'],
                'meta_og_type' => $project['meta_og_type']
            ];
            $post = $this->postService->createPost($postData);

            // Create Project
            $projectData = [
                'slug' => $project['slug'],
                'donation_form_id' => $project['donation_form_id'],
                'featured_image_id' => $featuredImage->id,
            ];
            $post->project()->create($projectData);

            // Create Project Contents
            $contents = [];
            foreach ($project['contents'] as $index => $content) {
                $contentBody = $content['body'];

                if ($content['type'] === 'image') {
                    $imagePath = $this->imageService->saveImageByUrl($contentBody['src']);
                    $imageData = [
                        'src' => $imagePath,
                        'alt_text' => $contentBody['alt_text']
                    ];
                    $image = $this->imageService->createImage($imageData);

                    $contentBody = $image->id;
                }

                $contents[] = [
                    'type' => $content['type'],
                    'body' => $contentBody,
                    'order' => $index
                ];
            }
            $post->contents()->createMany($contents);

            // Get the category of project
            $categories = $this->postCategoryService->getPostCategoriesBySlug([$project['categories'][0]['slug']], PostTypeEnum::PROJECT);
            $post->categories()->attach($categories?->pluck('id'));
        }
    }
}
