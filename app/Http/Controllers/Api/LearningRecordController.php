<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningRecord;
use Illuminate\Http\Request;

class LearningRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = LearningRecord::with(['quiz.term.category'])
            ->where('user_id', $request->user()->id)
            ->orderBy('answered_at', 'desc')
            ->paginate(30);

        return response()->json($records);
    }

    public function stats(Request $request)
    {
        $userId = $request->user()->id;

        $byCategory = LearningRecord::selectRaw('
                categories.id as category_id,
                categories.name as category_name,
                categories.color,
                COUNT(*) as total,
                SUM(CASE WHEN learning_records.is_correct = 1 THEN 1 ELSE 0 END) as correct
            ')
            ->join('quizzes', 'learning_records.quiz_id', '=', 'quizzes.id')
            ->join('terms', 'quizzes.term_id', '=', 'terms.id')
            ->join('categories', 'terms.category_id', '=', 'categories.id')
            ->where('learning_records.user_id', $userId)
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->get()
            ->map(function ($row) {
                $row->accuracy = $row->total > 0 ? round($row->correct / $row->total * 100, 1) : 0;
                return $row;
            });

        $totalAnswered = LearningRecord::where('user_id', $userId)->count();
        $totalCorrect  = LearningRecord::where('user_id', $userId)->where('is_correct', true)->count();

        $streak = $this->calculateStreak($userId);

        return response()->json([
            'total_answered' => $totalAnswered,
            'total_correct'  => $totalCorrect,
            'accuracy'       => $totalAnswered > 0 ? round($totalCorrect / $totalAnswered * 100, 1) : 0,
            'streak'         => $streak,
            'by_category'    => $byCategory,
        ]);
    }

    private function calculateStreak(int $userId): int
    {
        $dates = LearningRecord::where('user_id', $userId)
            ->selectRaw('DATE(answered_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();

        if (empty($dates)) return 0;

        $streak = 0;
        $today  = now()->format('Y-m-d');
        $check  = $dates[0] === $today ? $today : null;

        if (!$check) return 0;

        foreach ($dates as $date) {
            $expected = now()->subDays($streak)->format('Y-m-d');
            if ($date === $expected) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
