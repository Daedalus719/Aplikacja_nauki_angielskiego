<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SentenceWordPuzzle;
use Illuminate\Http\Request;

class SentenceController extends Controller
{
    public function index()
    {
        try {
            $sections = Section::all();
            return view('sentence_game.index', compact('sections'));
        } catch (\Exception $e) {
            return redirect()->route('sentence_game.index')->with('error', 'Wystąpił błąd podczas ładowania sekcji: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $section = Section::findOrFail($id);
            $sentence = SentenceWordPuzzle::where('section_id', $id)->inRandomOrder()->first();

            return view('sentence_game.play', compact('section', 'sentence'));
        } catch (\Exception $e) {
            return redirect()->route('sentence_game.index')->with('error', 'Wystąpił błąd podczas ładowania sekcji lub zdania: ' . $e->getMessage());
        }
    }

    public function allSentences($sectionId)
    {
        try {
            $section = Section::findOrFail($sectionId);
            $sentences = $section->sentences()->get();

            return view('sentence_game.all-sentences', compact('section', 'sentences'));
        } catch (\Exception $e) {
            return redirect()->route('sentence_game.index')->with('error', 'Wystąpił błąd podczas ładowania wszystkich zdań: ' . $e->getMessage());
        }
    }

    public function addSentence(Request $request, $section_id)
    {
        $validated = $request->validate([
            'sentence' => 'required|string',
        ]);

        try {
            SentenceWordPuzzle::create([
                'section_id' => $section_id,
                'sentence' => $validated['sentence'],
            ]);

            return redirect()->route('sentence_game.show', $section_id)->with('success', 'Zdanie zostało pomyślnie dodane!');
        } catch (\Exception $e) {
            return redirect()->route('sentence_game.show', $section_id)->with('error', 'Wystąpił błąd podczas dodawania zdania: ' . $e->getMessage());
        }
    }

    public function updateSentence(Request $request, $sentenceId)
    {
        $validated = $request->validate([
            'sentence' => 'required|string',
        ]);

        try {
            $sentence = SentenceWordPuzzle::findOrFail($sentenceId);
            $sentence->sentence = $validated['sentence'];
            $sentence->save();

            return response()->json(['success' => true, 'message' => 'Zdanie zostało pomyślnie zaktualizowane!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Wystąpił błąd podczas aktualizacji zdania: ' . $e->getMessage()], 500);
        }
    }

    public function deleteSentence($sentenceId)
    {
        try {
            $sentence = SentenceWordPuzzle::findOrFail($sentenceId);
            $sentence->delete();

            return response()->json(['success' => true, 'message' => 'Zdanie zostało pomyślnie usunięte!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Wystąpił błąd podczas usuwania zdania: ' . $e->getMessage()], 500);
        }
    }
}
