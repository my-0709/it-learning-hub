<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizSession;
use App\Repositories\Interfaces\LearningRecordRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Repositories\Interfaces\QuizSessionRepositoryInterface;

class QuizService
{
    public function __construct(
        private QuizRepositoryInterface        $quizRepository,
        private QuizSessionRepositoryInterface $sessionRepository,
        private LearningRecordRepositoryInterface $recordRepository
    ) {}

    public function getRandom(?int $categoryId, bool $weakMode, ?int $userId, array $excludeIds = []): ?Quiz
    {
        $weakQuizIds = null;

        if ($weakMode && $userId) {
            $ids = $this->recordRepository->getIncorrectQuizIds($userId);
            $weakQuizIds = $ids->isNotEmpty() ? $ids : null;
        }

        return $this->quizRepository->findRandom($categoryId, $weakQuizIds, $excludeIds);
    }

    public function startSession(int $userId, ?int $categoryId): QuizSession
    {
        return $this->sessionRepository->create($userId, $categoryId);
    }

    public function answer(QuizSession $session, int $userId, array $data): array
    {
        $quiz    = $this->quizRepository->findWithChoices($data['quiz_id']);
        $correct = $quiz->choices->firstWhere('id', $data['choice_id'])?->is_correct ?? false;

        $record = $this->recordRepository->create([
            'user_id'          => $userId,
            'quiz_id'          => $data['quiz_id'],
            'quiz_session_id'  => $session->id,
            'is_correct'       => $correct,
            'response_time_ms' => $data['response_time_ms'] ?? null,
            'answered_at'      => now(),
        ]);

        $this->sessionRepository->incrementCounts($session, $correct);

        return [
            'is_correct'     => $correct,
            'correct_choice' => $quiz->choices->firstWhere('is_correct', true),
            'explanation'    => $quiz->explanation,
            'record'         => $record,
        ];
    }

    public function endSession(QuizSession $session): QuizSession
    {
        return $this->sessionRepository->complete($session);
    }
}
