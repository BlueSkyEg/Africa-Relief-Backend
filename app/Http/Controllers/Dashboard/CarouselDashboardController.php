<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\CarouselSlide\CarouselSlide;
use App\Modules\CarouselSlide\Resources\CarouselSlideResource;
use App\Modules\Image\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarouselDashboardController extends Controller
{
    public function store(Request $request)
    {
        $image_file = $request->file('image');
        $image_path = $image_file->storeAs('images/' .date('Y/') . date('m/') . $image_file->getClientOriginalName());
        $image = Image::create([
            'src' => Str::after($image_path, '/'),
            'alt_text' => Str::beforeLast($image_file->getClientOriginalName(), '.')
        ]);
        $slide = CarouselSlide::create([
            'title' => $request->title,
            'description' => $request->description,
            'destination_label' => $request->destination_label,
            'destination_type' => $request->destination_type,
            'destination_slug' => $request->destination_slug,
            'carousel_type' => 'home_carousel',
            'image_id' => $image->id,
            'is_active' => 1
        ]);

        return response()->json(new CarouselSlideResource($slide));
    }
}
