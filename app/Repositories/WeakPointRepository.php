<?php

namespace App\Repositories;

use App\Models\LearningRecord;
use App\Repositories\Interfaces\WeakPointRepositoryInterface;
use Illuminate\Support\Collection;

class WeakPointRepository implements WeakPointRepositoryInterface
{
    public function getWeakPoints(int $userId): Collection
    {
        return LearningRecord::selectRaw('
                terms.id        as term_id,
                terms.name      as term_name,
                categories.name as category_name,
                categories.color,
                COUNT(*)        as total,
                SUM(CASE WHEN learning_records.is_correct = 1 THEN 1 ELSE 0 END) as correct
            ')
            ->join('quizzes',    'learning_records.quiz_id',  '=', 'quizzes.id')
            ->join('terms',      'quizzes.term_id',           '=', 'terms.id')
            ->join('categories', 'terms.category_id',         '=', 'categories.id')
            ->where('learning_records.user_id', $userId)
            ->groupBy('terms.id', 'terms.name', 'categories.name', 'categories.color')
            ->having('total', '>=', 2)
            ->get()
            ->map(function ($row) {
                $row->accuracy = $row->total > 0
                    ? round($row->correct / $row->total * 100, 1)
                    : 0;
                return $row;
            })
            ->sortBy('accuracy')
            ->take(20)
            ->values();
    }
}
