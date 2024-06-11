<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'english_word', 'polish_word'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
