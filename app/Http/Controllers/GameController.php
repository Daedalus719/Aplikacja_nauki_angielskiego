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
        $wordType = Word::inRandomOrder()->first()->word_type;

        $words = Word::where('word_type', $wordType)
            ->inRandomOrder()
            ->limit(2)
            ->get();

        return response()->json([
            'correct_word' => $words[0],
            'incorrect_word' => $words[1]
        ]);
    }

    public function scrabble()
    {
        $words = Word::inRandomOrder()->limit(3)->get();

        return view('games.scrabble', compact('words'));
    }

    public function flashcards()
    {
        $words = Word::inRandomOrder()->limit(5)->get();

        session(['flashcards' => $words->pluck('id')->toArray()]);

        return view('games.flashcards', compact('words'));
    }


    public function flashcardsTest()
    {
        $wordIds = session('flashcards', []);

        $words = Word::whereIn('id', $wordIds)->get();

        return view('games.flashcards-test', compact('words'));
    }



    public function checkAnswers(Request $request)
    {
        $results = [];

        foreach ($request->all() as $key => $answer) {
            if (strpos($key, 'word_') === 0) {
                $wordId = str_replace('word_', '', $key);
                $word = Word::find($wordId);

                $results[] = [
                    'id' => $wordId,
                    'correct' => strtolower($answer) === strtolower($word->english_word),
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
