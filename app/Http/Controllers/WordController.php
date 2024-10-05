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

            return redirect()->route('dictionary')->with('success', 'Wpis został pomyślnie dodany do bazy!');
        }

        $words = Word::orderBy('english_word', 'asc')->get();

        return view('dictionary', compact('words'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        $words = Word::where('english_word', 'LIKE', "%{$query}%")
            ->orWhere('polish_word', 'LIKE', "%{$query}%")
            ->get(['id', 'english_word', 'polish_word'])
            ->map(function ($word) {
                return [
                    'id' => $word->id,
                    'english_word' => $word->english_word,
                    'polish_word' => $word->polish_word,
                    'language' => strpos(strtolower($word->english_word), request()->get('query')) !== false ? 'english' : 'polish',
                ];
            });

        return response()->json($words);
    }

    public function show($id)
    {
        $word = Word::findOrFail($id);
        return view('words.show', compact('word'));
    }

    public function index()
    {
        $words = Word::all();
        return view('words.index', compact('words'));
    }

    public function edit(Word $word)
    {
        return view('words.edit', compact('word'));
    }

    public function update(Request $request, Word $word)
    {
        $word->update($request->only(['english_word', 'pronunciation', 'word_type', 'polish_word']));

        return response()->json(['success' => true, 'message' => 'Słowo zostało pomyślnie zaktualizowane.']);
    }

    public function destroy(Word $word)
    {
        $word->delete();
        return redirect()->route('dictionary')->with('success', 'Słowo zostało pomyślnie usunięte z bazy.');
    }

}
