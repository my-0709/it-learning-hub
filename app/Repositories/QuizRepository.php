<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use Illuminate\Support\Collection;

class QuizRepository implements QuizRepositoryInterface
{
    public function findRandom(?int $categoryId, ?Collection $weakQuizIds): ?Quiz
    {
        $query = Quiz::with(['term.category', 'choices'])
            ->when($categoryId, fn($q) => $q->whereHas(
                'term',
                fn($q2) => $q2->where('category_id', $categoryId)
            ));

        if ($weakQuizIds && $weakQuizIds->isNotEmpty()) {
            $query->whereIn('id', $weakQuizIds);
        }

        $quiz = $query->inRandomOrder()->first();

        if ($quiz) {
            $quiz->setRelation('choices', $quiz->choices->shuffle()->values());
        }

        return $quiz;
    }

    public function findWithChoices(int $id): Quiz
    {
        return Quiz::with('choices')->findOrFail($id);
    }
}
