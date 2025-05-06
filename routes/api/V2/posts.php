<?php

use App\Http\Controllers\V2\CarouselSlideController;
use App\Http\Controllers\V2\DonationCore\DonationFormController;
use App\Http\Controllers\V2\MobileController;
use App\Http\Controllers\V2\Post\BlogController;
use App\Http\Controllers\V2\Post\CareerController;
use App\Http\Controllers\V2\Post\PostCategoryController;
use App\Http\Controllers\V2\Post\ProjectController;
use App\Modules\PostCore\Blog\Services\BlogService;
use App\Modules\PostCore\Career\Services\CareerService;
use App\Modules\PostCore\Project\Services\ProjectService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

//Route::post('/create-blog-categories', function () {
//    $categories = json_decode(file_get_contents('db/blog-categories.json'), true)['data'];
//
//    foreach ($categories as $category) {
//        \App\Models\PostCategory::create([
//            'post_type' => \App\Enums\PostTypeEnum::BLOG->value,
//            'name' => $category['name'],
//            'slug' => $category['slug']
//        ]);
//    }
//    return 'Categories Created Successfully';
//});

//Route::post('/create-project-categories', function () {
//    $categories = json_decode(file_get_contents('db/project-categories.json'), true)['data'];
//
//    foreach ($categories as $category) {
//        \App\Models\PostCategory::create([
//            'post_type' => \App\Enums\PostTypeEnum::PROJECT->value,
//            'name' => $category['name'],
//            'slug' => $category['slug']
//        ]);
//    }
//    return 'Categories Created Successfully';
//});

//Route::post('/create-home-slider', function () {
//    $slides = json_decode(file_get_contents('db/home-slider.json'), true);
//    foreach (array_reverse($slides) as $slide) {
//        $image = \App\Models\Image::create([
//            'src' => date('Y/') . date('m/') . Str::afterLast($slide['image']['src'], '/'),
//            'alt_text' => $slide['summary']
//        ]);
//        \App\Models\CarouselSlide::create([
//            'title' => $slide['summary'],
//            'description' => $slide['description'],
//            'destination_label' => 'Donate Now',
//            'destination_type' => 'project_category',
//            'destination_slug' => Str::afterLast($slide['destination'], '/'),
//            'image_id' => $image->id,
//            'carousel_type' => \App\Enums\CarouselTypeEnum::Home_Carousel->value
//        ]);
//    }
//
//    return 'Home Slider Created Successfully';
//});

//Route::post('/create-blogs', [BlogService::class, 'createBlogsFromJsonFile']);

Route::post('/create-projects', [ProjectService::class, 'createProjectsFromJsonFile']);

//Route::post('/create-careers', [CareerService::class, 'createCareersFromJsonFile']);


/* --------------Blogs------------ */
Route::get('/blogs/categories', [PostCategoryController::class, 'getBlogCategories']);
Route::get('/blogs/related/{blogSlug}', [BlogController::class, 'getRelatedBlogs']);
Route::get('/blogs/gallery', [BlogController::class, 'getBlogsGallery']);
Route::get('/blogs/search/{searchTerm}', [BlogController::class, 'searchBlogs']);
Route::get('/blogs', [BlogController::class, 'getPublishedBlogs']);
Route::get('/blogs/{blogSlug}', [BlogController::class, 'getPublishedBlog']);


/* --------------Projects------------ */
Route::get('/projects/categories', [PostCategoryController::class, 'getProjectCategories']);
Route::get('/projects/related/{projectSlug}', [ProjectController::class, 'getRelatedProjects']);
Route::get('/projects/search/{searchTerm}', [ProjectController::class, 'searchProjects']);
Route::get('/projects', [ProjectController::class, 'getPublishedProjects']);
Route::get('/projects/{projectSlug}', [ProjectController::class, 'getPublishedProject']);


/* --------------Careers------------ */
Route::get('/careers', [CareerController::class, 'getPublishedCareers']);
Route::get('/careers/{careerSlug}', [CareerController::class, 'getPublishedCareer']);


/* --------------Carousels------------ */
Route::get('/carousels/{carouselType}', [CarouselSlideController::class, 'getCarousel']);


/* --------------Donation Forms------------ */
Route::get('/donation-forms/home', [DonationFormController::class, 'getHomePageDonationForm']);


/* --------------Mobile------------ */
Route::get('/mobile/home', [MobileController::class, 'getMobileHomeScreenData']);
