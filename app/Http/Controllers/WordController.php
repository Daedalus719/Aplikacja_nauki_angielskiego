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

        $words = Word::orderBy('english_word', 'asc')->paginate(60);

        return view('dictionary', compact('words'));
    }
    public function loadMoreWords(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 60);

        $words = Word::orderBy('english_word', 'asc')->offset($offset)->limit($limit)->get();

        return response()->json($words);
    }

    public function show($id)
    {
        $word = Word::findOrFail($id);
        return view('words.show', compact('word'));
    }

    public function index()
    {
        $words = Word::orderBy('english_word', 'asc')->get();
        return response()->json($words);
    }


    public function edit(Word $word)
    {
        return view('words.edit', compact('word'));
    }

    public function updateWord(Request $request, $id)
    {
        $word = Word::find($id);
        $word->update($request->all());

        return response()->json(['success' => true, 'word' => $word]);
    }

    public function deleteWord($id)
    {
        $word = Word::find($id);
        $word->delete();

        return response()->json(['success' => true]);
    }


    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');

        $words = Word::where('english_word', 'LIKE', '%' . $query . '%')
            ->orWhere('polish_word', 'LIKE', '%' . $query . '%')
            ->get(['english_word', 'polish_word']);

        return response()->json($words);
    }
}
