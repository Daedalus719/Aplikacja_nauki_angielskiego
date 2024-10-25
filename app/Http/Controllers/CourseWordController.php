<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Word;
use Illuminate\Http\Request;

class CourseWordController extends Controller
{
    public function show(Course $course)
    {
        try {
            $courseWords = $course->words;

            return view('course-words.show', compact('course', 'courseWords'));
        } catch (\Exception $e) {
            return redirect()->route('course.index')->with('error', 'Wystąpił błąd podczas wyświetlania słów kursu: ' . $e->getMessage());
        }
    }

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'word_id' => 'required|exists:words,id',
        ]);

        try {
            $word = Word::find($request->word_id);

            if ($course->words()->where('word_id', $word->id)->exists()) {
                return redirect()->route('course-words.show', $course)
                                 ->with('error', 'Słowo "' . $word->english_word . '" jest już dodane do kursu "' . $course->title . '"!');
            }

            $course->words()->attach($word->id);

            return redirect()->route('course-words.show', $course)
                             ->with('success', 'Słowo "' . $word->english_word . '" zostało pomyślnie dodane do kursu "' . $course->title . '"!');
        } catch (\Exception $e) {
            return redirect()->route('course-words.show', $course)
                             ->with('error', 'Wystąpił błąd podczas dodawania słowa do kursu: ' . $e->getMessage());
        }
    }


    public function searchWord(Request $request)
    {
        $query = $request->get('query');

        try {
            $words = Word::where('english_word', 'LIKE', '%' . $query . '%')->orderBy('english_word', 'asc')->get();

            return response()->json($words);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas wyszukiwania słów: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Course $course, Word $word)
    {
        try {
            $course->words()->detach($word->id);

            return redirect()->route('course-words.show', $course)
                             ->with('success', 'Słowo "' . $word->english_word . '" zostało pomyślnie usunięte z kursu "' . $course->title . '".');
        } catch (\Exception $e) {
            return redirect()->route('course-words.show', $course)
                             ->with('error', 'Wystąpił błąd podczas usuwania słowa z kursu: ' . $e->getMessage());
        }
    }
}
