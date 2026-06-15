<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FavoriteRepositoryInterface
{
    public function getFavoriteIds(int $userId): array;

    public function isFavorite(int $userId, int $termId): bool;

    public function paginateFavoriteTerms(int $userId): LengthAwarePaginator;

    public function toggle(int $userId, int $termId): bool;
}
