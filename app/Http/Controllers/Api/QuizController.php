<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningRecord;
use App\Models\Quiz;
use App\Models\QuizSession;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function random(Request $request)
    {
        $query = Quiz::with(['term.category', 'choices'])
            ->when($request->category_id, function ($q) use ($request) {
                $q->whereHas('term', fn($q2) => $q2->where('category_id', $request->category_id));
            });

        if ($request->weak_mode && $request->user()) {
            $weakQuizIds = LearningRecord::where('user_id', $request->user()->id)
                ->where('is_correct', false)
                ->pluck('quiz_id');
            if ($weakQuizIds->isNotEmpty()) {
                $query->whereIn('id', $weakQuizIds);
            }
        }

        $quiz = $query->inRandomOrder()->first();

        if (!$quiz) {
            return response()->json(['message' => 'クイズが見つかりません'], 404);
        }

        $choices = $quiz->choices->shuffle()->values();
        $quiz->setRelation('choices', $choices);

        return response()->json($quiz);
    }

    public function startSession(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $session = QuizSession::create([
            'user_id'     => $request->user()->id,
            'category_id' => $data['category_id'] ?? null,
            'status'      => 'in_progress',
        ]);

        return response()->json($session, 201);
    }

    public function answer(Request $request, QuizSession $quizSession)
    {
        $this->authorize('update', $quizSession);

        $data = $request->validate([
            'quiz_id'         => 'required|exists:quizzes,id',
            'choice_id'       => 'required|exists:choices,id',
            'response_time_ms'=> 'nullable|integer',
        ]);

        $quiz      = Quiz::with('choices')->findOrFail($data['quiz_id']);
        $correct   = $quiz->choices->firstWhere('id', $data['choice_id'])?->is_correct ?? false;

        $record = LearningRecord::create([
            'user_id'          => $request->user()->id,
            'quiz_id'          => $data['quiz_id'],
            'quiz_session_id'  => $quizSession->id,
            'is_correct'       => $correct,
            'response_time_ms' => $data['response_time_ms'] ?? null,
            'answered_at'      => now(),
        ]);

        $quizSession->increment('total_count');
        if ($correct) $quizSession->increment('correct_count');

        $correctChoice = $quiz->choices->firstWhere('is_correct', true);

        return response()->json([
            'is_correct'     => $correct,
            'correct_choice' => $correctChoice,
            'explanation'    => $quiz->explanation,
            'record'         => $record,
        ]);
    }

    public function endSession(Request $request, QuizSession $quizSession)
    {
        $this->authorize('update', $quizSession);

        $quizSession->update([
            'status'   => 'completed',
            'ended_at' => now(),
        ]);

        return response()->json($quizSession->fresh());
    }
}
