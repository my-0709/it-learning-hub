<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(private QuizService $service) {}

    public function random(Request $request): JsonResponse
    {
        $excludeIds = array_filter(array_map('intval', (array) $request->input('exclude_ids', [])));

        $quiz = $this->service->getRandom(
            $request->integer('category_id') ?: null,
            (bool) $request->input('weak_mode'),
            $request->user()?->id,
            array_values($excludeIds)
        );

        if (!$quiz) {
            return response()->json(['message' => 'クイズが見つかりません'], 404);
        }

        return response()->json($quiz);
    }

    public function startSession(Request $request): JsonResponse
    {
        $data    = $request->validate(['category_id' => 'nullable|exists:categories,id']);
        $session = $this->service->startSession($request->user()->id, $data['category_id'] ?? null);

        return response()->json($session, 201);
    }

    public function answer(Request $request, QuizSession $quizSession): JsonResponse
    {
        $this->authorize('update', $quizSession);

        $data   = $request->validate([
            'quiz_id'          => 'required|exists:quizzes,id',
            'choice_id'        => 'required|exists:choices,id',
            'response_time_ms' => 'nullable|integer',
        ]);
        $result = $this->service->answer($quizSession, $request->user()->id, $data);

        return response()->json($result);
    }

    public function endSession(Request $request, QuizSession $quizSession): JsonResponse
    {
        $this->authorize('update', $quizSession);
        $session = $this->service->endSession($quizSession);

        return response()->json($session);
    }
}
