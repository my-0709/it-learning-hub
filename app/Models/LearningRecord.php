<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningRecord extends Model
{
    protected $fillable = [
        'user_id', 'quiz_id', 'quiz_session_id',
        'is_correct', 'response_time_ms', 'answered_at',
    ];

    protected $casts = [
        'is_correct'  => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function quizSession()
    {
        return $this->belongsTo(QuizSession::class);
    }
}
