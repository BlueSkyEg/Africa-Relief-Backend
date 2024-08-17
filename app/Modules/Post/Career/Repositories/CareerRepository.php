<?php

namespace App\Modules\Post\Career\Repositories;

use App\Modules\Post\Career\Career;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CareerRepository
{

    /**
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return Career::when($published, function (Builder $query) {
            return $query->whereRelation('post', 'published', '=', 1);
        })
            ->latest('id')
            ->paginate($perPage);
    }


    /**
     * @param string $careerSlug
     * @param bool|null $published
     * @return Career|null
     */
    public function getBySlug(string $careerSlug, bool|null $published = null): ?Career
    {
        return Career::when($published, function (Builder $query) {
            return $query->whereRelation('post', 'published', '=', 1);
        })
            ->where('slug', $careerSlug)->first();
    }
}
