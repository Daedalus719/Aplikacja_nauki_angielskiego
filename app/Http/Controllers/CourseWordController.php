<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Word;
use Illuminate\Http\Request;

class CourseWordController extends Controller
{
    public function show(Course $course)
    {
        $courseWords = $course->words;

        return view('course-words.show', compact('course', 'courseWords'));
    }

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'word_id' => 'required|exists:words,id',
        ]);

        $course->words()->attach($request->word_id);

        return redirect()->route('course-words.show', $course)
                         ->with('success', 'Word added to the course!');
    }

    public function searchWord(Request $request)
    {
        $query = $request->get('query');

        $words = Word::where('english_word', 'LIKE', '%' . $query . '%')->get();

        return response()->json($words);
    }
}
