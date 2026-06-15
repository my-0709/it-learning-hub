<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPlan extends Model
{
    protected $fillable = ['user_id', 'content', 'ai_generated'];

    protected $casts = [
        'content'      => 'array',
        'ai_generated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
