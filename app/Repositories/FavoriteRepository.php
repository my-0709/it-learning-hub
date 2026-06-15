<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Models\Term;
use App\Repositories\Interfaces\FavoriteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function getFavoriteIds(int $userId): array
    {
        return Favorite::where('user_id', $userId)->pluck('term_id')->toArray();
    }

    public function isFavorite(int $userId, int $termId): bool
    {
        return Favorite::where('user_id', $userId)->where('term_id', $termId)->exists();
    }

    public function paginateFavoriteTerms(int $userId): LengthAwarePaginator
    {
        $terms = Term::with(['category', 'tags'])
            ->whereHas('favoritedBy', fn($q) => $q->where('user_id', $userId))
            ->paginate(20);

        $terms->getCollection()->transform(function ($term) {
            $term->is_favorite = true;
            return $term;
        });

        return $terms;
    }

    public function toggle(int $userId, int $termId): bool
    {
        $existing = Favorite::where('user_id', $userId)->where('term_id', $termId)->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        Favorite::create(['user_id' => $userId, 'term_id' => $termId]);
        return true;
    }
}
