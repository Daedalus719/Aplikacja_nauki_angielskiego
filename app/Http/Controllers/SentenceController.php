<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SentenceWordPuzzle;
use Illuminate\Http\Request;

class SentenceController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('sentence_game.index', compact('sections'));
    }


    public function show($id)
    {
        $section = Section::findOrFail($id);

        $sentence = SentenceWordPuzzle::where('section_id', $id)->inRandomOrder()->first();

        return view('sentence_game.play', compact('section', 'sentence'));
    }

    public function allSentences($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        $sentences = $section->sentences()->get();

        return view('sentence_game.all-sentences', compact('section', 'sentences'));
    }



    public function addSentence(Request $request, $section_id)
    {
        $validated = $request->validate([
            'sentence' => 'required|string',
        ]);

        SentenceWordPuzzle::create([
            'section_id' => $section_id,
            'sentence' => $validated['sentence'],
        ]);

        return redirect()->route('sentence_game.show', $section_id)->with('success', 'Sentence added successfully!');
    }

    public function updateSentence(Request $request, $sentenceId)
    {
        $sentence = SentenceWordPuzzle::findOrFail($sentenceId);
        $sentence->sentence = $request->sentence;
        $sentence->save();

        return response()->json(['success' => true]);
    }

    public function deleteSentence($sentenceId)
    {
        $sentence = SentenceWordPuzzle::findOrFail($sentenceId);
        $sentence->delete();

        return response()->json(['success' => true]);
    }
}
