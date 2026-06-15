<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['term_id', 'question', 'explanation'];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function learningRecords()
    {
        return $this->hasMany(LearningRecord::class);
    }
}
