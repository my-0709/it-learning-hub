<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $fillable = ['quiz_id', 'body', 'is_correct'];

    protected $casts = ['is_correct' => 'boolean'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
