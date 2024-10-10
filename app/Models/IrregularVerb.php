<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrregularVerb extends Model
{
    use HasFactory;

    protected $fillable = ['verb_1st_form', 'verb_2nd_form', 'verb_3rd_form', 'polish_translation'];
}
