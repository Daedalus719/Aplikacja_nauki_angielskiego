<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentenceRule extends Model
{
    use HasFactory;

    protected $table = 'sentence_rules';

    protected $fillable = ['section_id', 'rule_text'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
