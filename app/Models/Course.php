<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];


    public function words()
    {
        return $this->belongsToMany(Word::class, 'course_word');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
