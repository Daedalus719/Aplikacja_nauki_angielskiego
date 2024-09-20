<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function dictionary(Request $request)
{
    if ($request->isMethod('post')) {
        $request->validate([
            'polish_word' => 'required|string|max:255',
            'english_word' => 'required|string|max:255',
            'pronunciation' => 'nullable|string|max:255',
            'word_type' => 'required|string|max:255',
        ]);

        Word::create([
            'polish_word' => $request->polish_word,
            'english_word' => $request->english_word,
            'pronunciation' => $request->pronunciation,
            'word_type' => $request->word_type,
        ]);

        return redirect()->route('dictionary')->with('success', 'Wpis został dodany do bazy!');
    }

    $words = Word::all();

    return view('dictionary', compact('words'));
}


}
