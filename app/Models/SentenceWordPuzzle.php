<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentenceWordPuzzle extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'sentence'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
