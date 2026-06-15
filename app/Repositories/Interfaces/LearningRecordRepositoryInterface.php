<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface LearningRecordRepositoryInterface
{
    public function paginate(int $userId): LengthAwarePaginator;

    public function create(array $data): \App\Models\LearningRecord;

    public function getStatsByCategory(int $userId): Collection;

    public function getTotals(int $userId): array;

    public function getAnsweredDates(int $userId): array;

    public function getIncorrectQuizIds(int $userId): Collection;

    public function deleteByUser(int $userId): void;
}
