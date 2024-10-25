<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return view('games.index');
    }

    public function translationGame()
    {
        return view('games.translation');
    }

    public function getRandomWords()
    {
        try {
            $wordType = Word::inRandomOrder()->first()->word_type;

            $words = Word::where('word_type', $wordType)
                ->inRandomOrder()
                ->limit(2)
                ->get();

            return response()->json([
                'correct_word' => $words[0],
                'incorrect_word' => $words[1]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania losowych słów: ' . $e->getMessage()], 500);
        }
    }

    public function scrabble()
    {
        return view('games.scrabble');
    }

    public function fetchScrabbleWords(Request $request)
    {
        $wordCount = $request->get('wordCount');
        $wordTypes = $request->get('wordTypes');

        if (!$wordCount || !$wordTypes) {
            return response()->json(['error' => 'Nieprawidłowe dane wejściowe'], 400);
        }

        try {
            $words = Word::whereIn('word_type', explode(',', $wordTypes))
                ->inRandomOrder()
                ->limit($wordCount)
                ->get(['english_word', 'polish_word']);

            return response()->json(['words' => $words]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania słów: ' . $e->getMessage()], 500);
        }
    }

    public function flashcards()
    {
        try {
            $words = Word::inRandomOrder()->limit(5)->get();

            session(['flashcards' => $words->pluck('id')->toArray()]);

            return view('games.flashcards', compact('words'));
        } catch (\Exception $e) {
            return redirect()->route('games.index')->with('error', 'Wystąpił błąd podczas przygotowywania fiszek: ' . $e->getMessage());
        }
    }

    public function flashcardsTest()
    {
        try {
            $wordIds = session('flashcards', []);

            $words = Word::whereIn('id', $wordIds)->get();

            return view('games.flashcards-test', compact('words'));
        } catch (\Exception $e) {
            return redirect()->route('games.index')->with('error', 'Wystąpił błąd podczas ładowania testu z fiszkami: ' . $e->getMessage());
        }
    }

    public function checkAnswers(Request $request)
    {
        $results = [];

        try {
            foreach ($request->all() as $key => $answer) {
                if (strpos($key, 'word_') === 0) {
                    $wordId = str_replace('word_', '', $key);
                    $word = Word::find($wordId);

                    if ($word) {
                        $results[] = [
                            'id' => $wordId,
                            'correct' => strtolower($answer) === strtolower($word->english_word),
                        ];
                    } else {
                        $results[] = [
                            'id' => $wordId,
                            'correct' => false,
                            'error' => 'Nie znaleziono słowa.',
                        ];
                    }
                }
            }

            return response()->json(['results' => $results]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wystąpił błąd podczas sprawdzania odpowiedzi: ' . $e->getMessage()], 500);
        }
    }
}
