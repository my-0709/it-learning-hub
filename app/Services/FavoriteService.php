<?php

namespace App\Services;

use App\Repositories\Interfaces\FavoriteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FavoriteService
{
    public function __construct(
        private FavoriteRepositoryInterface $repository
    ) {}

    public function getList(int $userId): LengthAwarePaginator
    {
        return $this->repository->paginateFavoriteTerms($userId);
    }

    public function toggle(int $userId, int $termId): bool
    {
        return $this->repository->toggle($userId, $termId);
    }
}
