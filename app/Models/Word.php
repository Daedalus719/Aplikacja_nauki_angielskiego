<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = ['english_word', 'word_type', 'polish_word'];

    public function courses()
{
    return $this->belongsToMany(Course::class, 'course_word');
}

}
