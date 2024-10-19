<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function sentences()
    {
        return $this->hasMany(SentenceWordPuzzle::class, 'section_id');
    }
}
