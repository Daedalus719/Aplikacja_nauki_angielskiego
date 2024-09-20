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

    public function edit(Course $course, Word $word)
    {
        return view('words.edit', compact('course', 'word'));
    }

    public function update(Request $request, Course $course, Word $word)
    {
        $request->validate([
            'english_word' => 'required|string|max:255',
            'polish_word' => 'required|string|max:255',
        ]);

        $word->update($request->all());

        return redirect()->route('course.show', $course);
    }

    public function destroy(Course $course, Word $word)
    {
        $word->delete();

        return redirect()->route('course.show', $course);
    }

    public function dictionary()
    {
        $words = Word::all();

        return view('dictionary', compact('words'));
    }
}
