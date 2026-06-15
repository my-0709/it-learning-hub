<?php

namespace App\Services;

use App\Repositories\Interfaces\LearningRecordRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LearningRecordService
{
    public function __construct(
        private LearningRecordRepositoryInterface $repository
    ) {}

    public function getHistory(int $userId): LengthAwarePaginator
    {
        return $this->repository->paginate($userId);
    }

    public function getStats(int $userId): array
    {
        $totals     = $this->repository->getTotals($userId);
        $byCategory = $this->repository->getStatsByCategory($userId);
        $streak     = $this->calculateStreak($userId);

        return [
            'total_answered' => $totals['total'],
            'total_correct'  => $totals['correct'],
            'accuracy'       => $totals['total'] > 0
                ? round($totals['correct'] / $totals['total'] * 100, 1)
                : 0,
            'streak'         => $streak,
            'by_category'    => $byCategory,
        ];
    }

    private function calculateStreak(int $userId): int
    {
        $dates = $this->repository->getAnsweredDates($userId);

        if (empty($dates)) {
            return 0;
        }

        if ($dates[0] !== now()->format('Y-m-d')) {
            return 0;
        }

        $streak = 0;
        foreach ($dates as $date) {
            if ($date === now()->subDays($streak)->format('Y-m-d')) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
