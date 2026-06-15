<?php

namespace App\Services;

use App\Models\Term;
use App\Repositories\Interfaces\FavoriteRepositoryInterface;
use App\Repositories\Interfaces\TermRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TermService
{
    public function __construct(
        private TermRepositoryInterface     $termRepository,
        private FavoriteRepositoryInterface $favoriteRepository
    ) {}

    public function getList(array $filters, ?int $userId): LengthAwarePaginator
    {
        $favoriteIds = $userId
            ? $this->favoriteRepository->getFavoriteIds($userId)
            : [];

        return $this->termRepository->paginate($filters, $favoriteIds);
    }

    public function getDetail(Term $term, ?int $userId): Term
    {
        $this->termRepository->loadRelations($term);

        $term->is_favorite = $userId
            ? $this->favoriteRepository->isFavorite($userId, $term->id)
            : false;

        return $term;
    }
}
