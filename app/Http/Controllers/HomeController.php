<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');

        try {
            $words = Word::where('english_word', 'LIKE', '%' . $query . '%')
                ->orWhere('polish_word', 'LIKE', '%' . $query . '%')
                ->orderByRaw('LENGTH(english_word) ASC, english_word ASC')
                ->take(30)
                ->get(['id', 'english_word', 'polish_word', 'word_type']);

            return response()->json($words);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Błąd podczas pobierania sugestii: ' . $e->getMessage()], 500);
        }
    }

    public function showWordDetails($id)
    {
        try {
            $word = Word::findOrFail($id);
            return response()->json($word);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Błąd podczas pobierania szczegółów słowa: ' . $e->getMessage()], 500);
        }
    }

    public function updateWord(Request $request, $id)
    {
        try {
            $word = Word::findOrFail($id);
            $word->update($request->all());
            return response()->json(['success' => true, 'message' => 'Słowo zostało pomyślnie zaktualizowane.', 'word' => $word]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Błąd podczas aktualizacji słowa: ' . $e->getMessage()], 500);
        }
    }

    public function deleteWord($id)
    {
        try {
            $word = Word::findOrFail($id);
            $word->delete();
            return response()->json(['success' => true, 'message' => 'Słowo zostało pomyślnie usunięte.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Błąd podczas usuwania słowa: ' . $e->getMessage()], 500);
        }
    }
}
