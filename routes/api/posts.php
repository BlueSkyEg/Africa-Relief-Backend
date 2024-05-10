<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/get-posts', function () {
//    return \App\Models\Post::whereHas('project')->paginate(10);
//    return \App\Models\Blog::all();
//        return response()->json(\App\Models\PostCategory::where('id', 1)->pluck('meta_description')->first());
//    return \App\Models\Blog::first()->post->images()->createMany([
//        ['src' => 'new-image-from-code.webp', 'alt_text' => 'This is alternative text for new image'],
//        ['src' => 'new-image2-from-code.jpg', 'alt_text' => 'This is alternative text for new image 2']
//    ]);
});

Route::post('/create-blog-categories', function () {
    $categories = json_decode(file_get_contents('db/blog-categories.json'), true)['data'];

    foreach ($categories as $category) {
        \App\Models\PostCategory::create([
            'post_type' => \App\Enums\PostTypeEnum::BLOG->value,
            'name' => $category['name'],
            'slug' => $category['slug']
        ]);
    }
    return 'Categories Created Successfully';
});

Route::post('create-blogs', function () {
    $blogs = json_decode(file_get_contents('db/blogs.json'), true);
    foreach ($blogs as $blogObj) {
        $featuredImage = \App\Models\PostImage::create([
            'src' => date('Y/') . date('m/') .Str::afterLast($blogObj['featuredImage']['src'], '/'),
            'alt_text' => $blogObj['featuredImage']['alt'],
        ]);

        $blog = \App\Models\Blog::create([
            'slug' => $blogObj['slug'],
            'location' => $blogObj['location'],
            'implementation_date' => $blogObj['implementationDate'],
            'donation_form_id' => $blogObj['donationForm']['id'],
            'featured_image_id' => $featuredImage->id,
        ]);

        $post = $blog->post()->create([
            'title' => $blogObj['title'],
            'excerpt' => null
        ]);

        $post->created_at = \Carbon\Carbon::parse($blogObj['date'])->toDateTimeString();
        $post->save();

        $contents = [];
        foreach ($blogObj['content'] as $content) {
            $newContent = [
                'markdown_sentences' => shell_exec("## " . $content['heading'] . "\n" . $content['description']),
                'json_sentences' => [
                    [
                        'type' => 'h2',
                        'value' => $content['heading']
                    ],
                    [
                        'type' => 'p',
                        'value' => $content['description']
                    ],
                ],
            ];
        }

        $post->contents()->createMany([
            [],
            []
        ]);

        $post->images()->createMany([
            [],
            []
        ]);

        $post->categories()->attach([1,2,3]);
    }
});
