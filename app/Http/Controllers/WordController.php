<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'english_word' => 'required|string|max:255',
            'polish_word' => 'required|string|max:255',
        ]);

        $course->words()->create($request->all());

        return redirect()->route('course.show', $course);
    }
}
