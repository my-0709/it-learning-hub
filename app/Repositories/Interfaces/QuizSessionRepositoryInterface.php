<?php

namespace App\Repositories\Interfaces;

use App\Models\QuizSession;

interface QuizSessionRepositoryInterface
{
    public function create(int $userId, ?int $categoryId): QuizSession;

    public function incrementCounts(QuizSession $session, bool $correct): void;

    public function complete(QuizSession $session): QuizSession;

    public function deleteByUser(int $userId): void;
}
