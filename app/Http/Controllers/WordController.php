<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WordController extends Controller
{
    public function dictionary(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'polish_word' => 'required|string|max:255',
                'english_word' => 'required|string|max:255',
                'word_type' => 'required|string|max:255',
            ]);

            $existingWord = Word::where('english_word', $request->english_word)
                ->where('polish_word', $request->polish_word)
                ->first();

            if ($existingWord) {
                return redirect()->route('dictionary')->with('error', 'Słowo "' . $request->english_word . '" już istnieje w bazie.');
            }

            try {
                Word::create([
                    'polish_word' => $request->polish_word,
                    'english_word' => $request->english_word,
                    'word_type' => $request->word_type,
                ]);

                return redirect()->route('dictionary')->with('success', 'Słowo "' . $request->english_word . '" zostało pomyślnie dodane do bazy!');
            } catch (\Exception $e) {
                return redirect()->route('dictionary')->with('error', 'Wystąpił błąd podczas dodawania słowa: ' . $e->getMessage());
            }
        }

        try {
            $words = Word::orderBy('english_word', 'asc')->paginate(60);

            return view('dictionary', compact('words'));
        } catch (\Exception $e) {
            return redirect()->route('dictionary')->with('error', 'Wystąpił błąd podczas ładowania słownika: ' . $e->getMessage());
        }
    }


    public function loadMoreWords(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 60);

        try {
            $words = Word::orderBy('english_word', 'asc')->offset($offset)->limit($limit)->get();

            if ($words->isEmpty()) {
                return response()->json(['message' => 'No more words found.'], 204);
            }

            return response()->json($words);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching word details: ' . $e->getMessage()], 500);
        }
    }


    public function index()
    {
        try {
            $words = Word::orderBy('english_word', 'asc')->get();

            return response()->json($words);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania słów: ' . $e->getMessage()], 500);
        }
    }

    public function edit(Word $word)
    {
        try {
            return view('words.edit', compact('word'));
        } catch (\Exception $e) {
            return redirect()->route('dictionary')->with('error', 'Wystąpił błąd podczas edytowania słowa: ' . $e->getMessage());
        }
    }

    public function updateWord(Request $request, $id)
    {
        try {
            $word = Word::findOrFail($id);
            $word->update($request->all());

            return response()->json(['success' => true, 'word' => $word]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas aktualizacji słowa: ' . $e->getMessage()], 500);
        }
    }

    public function deleteWord($id)
    {
        try {
            $word = Word::findOrFail($id);
            $word->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas usuwania słowa: ' . $e->getMessage()], 500);
        }
    }

    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');

        try {
            $words = Word::where('english_word', 'LIKE', '%' . $query . '%')
                ->orWhere('polish_word', 'LIKE', '%' . $query . '%')
                ->orderBy('english_word', 'asc')
                ->take(20)
                ->get(['english_word', 'polish_word']);

            return response()->json($words);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas wyszukiwania sugestii: ' . $e->getMessage()], 500);
        }
    }
}
