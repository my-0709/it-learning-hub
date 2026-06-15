<?php

namespace App\Repositories;

use App\Models\QuizSession;
use App\Repositories\Interfaces\QuizSessionRepositoryInterface;

class QuizSessionRepository implements QuizSessionRepositoryInterface
{
    public function create(int $userId, ?int $categoryId): QuizSession
    {
        return QuizSession::create([
            'user_id'     => $userId,
            'category_id' => $categoryId,
            'status'      => 'in_progress',
        ]);
    }

    public function incrementCounts(QuizSession $session, bool $correct): void
    {
        $session->increment('total_count');
        if ($correct) {
            $session->increment('correct_count');
        }
    }

    public function complete(QuizSession $session): QuizSession
    {
        $session->update([
            'status'   => 'completed',
            'ended_at' => now(),
        ]);
        return $session->fresh();
    }

    public function deleteByUser(int $userId): void
    {
        QuizSession::where('user_id', $userId)->delete();
    }
}
