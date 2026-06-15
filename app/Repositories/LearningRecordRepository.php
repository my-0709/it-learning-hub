<?php

namespace App\Repositories;

use App\Models\LearningRecord;
use App\Repositories\Interfaces\LearningRecordRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LearningRecordRepository implements LearningRecordRepositoryInterface
{
    public function paginate(int $userId): LengthAwarePaginator
    {
        return LearningRecord::with(['quiz.term.category'])
            ->where('user_id', $userId)
            ->orderBy('answered_at', 'desc')
            ->paginate(30);
    }

    public function create(array $data): LearningRecord
    {
        return LearningRecord::create($data);
    }

    public function getStatsByCategory(int $userId): Collection
    {
        return LearningRecord::selectRaw('
                categories.id   as category_id,
                categories.name as category_name,
                categories.color,
                COUNT(*)        as total,
                SUM(CASE WHEN learning_records.is_correct = 1 THEN 1 ELSE 0 END) as correct
            ')
            ->join('quizzes',    'learning_records.quiz_id',   '=', 'quizzes.id')
            ->join('terms',      'quizzes.term_id',            '=', 'terms.id')
            ->join('categories', 'terms.category_id',          '=', 'categories.id')
            ->where('learning_records.user_id', $userId)
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->get()
            ->map(function ($row) {
                $row->accuracy = $row->total > 0
                    ? round($row->correct / $row->total * 100, 1)
                    : 0;
                return $row;
            });
    }

    public function getTotals(int $userId): array
    {
        return [
            'total'   => LearningRecord::where('user_id', $userId)->count(),
            'correct' => LearningRecord::where('user_id', $userId)->where('is_correct', true)->count(),
        ];
    }

    public function getAnsweredDates(int $userId): array
    {
        return LearningRecord::where('user_id', $userId)
            ->selectRaw('DATE(answered_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();
    }

    public function getIncorrectQuizIds(int $userId): Collection
    {
        return LearningRecord::where('user_id', $userId)
            ->where('is_correct', false)
            ->pluck('quiz_id');
    }

    public function deleteByUser(int $userId): void
    {
        LearningRecord::where('user_id', $userId)->delete();
    }
}
