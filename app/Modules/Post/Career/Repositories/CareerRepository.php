<?php

namespace App\Modules\Post\Career\Repositories;

use App\Models\Career;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CareerRepository
{
    public function getCareers(int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return Career::when($published, function (Builder $query) {
            return $query->whereRelation('post', 'published', '=', 1);
        })
            ->latest('id')
            ->paginate($perPage);
    }

    public function getCareer(string $careerSlug, bool|null $published = null): Career|null
    {
        return Career::when($published, function (Builder $query) {
            return $query->whereRelation('post', 'published', '=', 1);
        })
            ->where('slug', $careerSlug)->first();
    }
}
