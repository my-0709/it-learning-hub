<?php

namespace App\Repositories\Interfaces;

use App\Models\Term;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TermRepositoryInterface
{
    public function paginate(array $filters, array $favoriteIds): LengthAwarePaginator;

    public function loadRelations(Term $term): Term;
}
