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
                         ->with('success', 'Słowo zostało pomyslnie dodane do kursu!');
    }

    public function searchWord(Request $request)
    {
        $query = $request->get('query');

        $words = Word::where('english_word', 'LIKE', '%' . $query . '%')->orderBy('english_word', 'asc')->get();

        return response()->json($words);
    }

    public function destroy(Course $course, Word $word)
    {
        $course->words()->detach($word->id);

        return redirect()->route('course-words.show', $course)
                         ->with('success', 'Słowo zostało pomyślnie usunięte z kursu.');
    }
}
