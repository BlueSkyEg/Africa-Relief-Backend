<?php

namespace App\Http\Controllers\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CarouselSlideResource;
use App\Models\CarouselSlide;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarouselDashboardController extends Controller
{
    public function store(Request $request)
    {
        $image_file = $request->file('image');
        $image_name = Str::beforeLast($image_file->getClientOriginalName(), '.');
        $image_path = $image_file->storeAs('images/' .date('Y/') . date('m/') . Str::slug($image_name) . '.' . $image_file->getClientOriginalExtension());
        $image = Image::create([
            'src' => Str::after($image_path, '/'),
            'alt_text' => $image_name
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
