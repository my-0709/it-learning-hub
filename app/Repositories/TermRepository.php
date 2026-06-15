<?php

namespace App\Repositories;

use App\Models\Term;
use App\Repositories\Interfaces\TermRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TermRepository implements TermRepositoryInterface
{
    public function paginate(array $filters, array $favoriteIds): LengthAwarePaginator
    {
        $terms = Term::with(['category', 'tags'])
            ->when($filters['category_id'] ?? null, fn($q, $v) => $q->where('category_id', $v))
            ->when($filters['q'] ?? null, fn($q, $v) => $q->where(function ($query) use ($v) {
                $query->where('name', 'like', "%{$v}%")
                      ->orWhere('definition', 'like', "%{$v}%");
            }))
            ->when($filters['difficulty'] ?? null, fn($q, $v) => $q->where('difficulty', $v))
            ->orderBy('name')
            ->paginate(20);

        $terms->getCollection()->transform(function ($term) use ($favoriteIds) {
            $term->is_favorite = in_array($term->id, $favoriteIds);
            return $term;
        });

        return $terms;
    }

    public function loadRelations(Term $term): Term
    {
        return $term->load(['category', 'tags']);
    }
}
