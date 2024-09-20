<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseWord extends Model
{
    protected $table = 'course_word';

    protected $fillable = ['course_id', 'word_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}
