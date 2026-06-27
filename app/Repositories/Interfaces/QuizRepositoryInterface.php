<?php

namespace App\Repositories\Interfaces;

use App\Models\Quiz;
use Illuminate\Support\Collection;

interface QuizRepositoryInterface
{
    public function findRandom(?int $categoryId, ?Collection $weakQuizIds, array $excludeIds = []): ?Quiz;

    public function findWithChoices(int $id): Quiz;
}
