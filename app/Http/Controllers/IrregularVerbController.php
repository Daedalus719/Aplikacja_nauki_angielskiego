<?php

namespace App\Http\Controllers;

use App\Models\IrregularVerb;
use Illuminate\Http\Request;

class IrregularVerbController extends Controller
{
    public function index()
    {
        try {
            $irregularVerbs = IrregularVerb::orderBy('verb_1st_form', 'asc')->get();
            return view('irregular-verbs.index', compact('irregularVerbs'));
        } catch (\Exception $e) {
            return redirect()->route('irregular-verbs.index')->with('error', 'Wystąpił błąd podczas ładowania listy czasowników nieregularnych: ' . $e->getMessage());
        }
    }

    public function showTaskPage()
    {
        try {
            $verbs = IrregularVerb::inRandomOrder()->limit(5)->get();
            return view('irregular-verbs.tasks', compact('verbs'));
        } catch (\Exception $e) {
            return redirect()->route('irregular-verbs.index')->with('error', 'Wystąpił błąd podczas ładowania zadań: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'verb_1st_form' => 'required|string|max:255',
            'verb_2nd_form' => 'required|string|max:255',
            'verb_3rd_form' => 'required|string|max:255',
            'polish_translation' => 'required|string|max:255',
        ]);

        try {
            $duplicate = IrregularVerb::where('verb_1st_form', $request->verb_1st_form)
                ->where('verb_2nd_form', $request->verb_2nd_form)
                ->where('verb_3rd_form', $request->verb_3rd_form)
                ->exists();

            if ($duplicate) {
                return redirect()->route('irregular-verbs.index')->with('error', 'Wpis z tymi czasownikami już istnieje w bazie!');
            }

            $verb = IrregularVerb::create($request->all());
            return redirect()->route('irregular-verbs.index')->with('success', 'Wpis został z powodzeniem dodany do bazy!');
        } catch (\Exception $e) {
            return redirect()->route('irregular-verbs.index')->with('error', 'Wystąpił błąd podczas dodawania wpisu: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'verb_1st_form' => 'required|string',
            'verb_2nd_form' => 'required|string',
            'verb_3rd_form' => 'required|string',
            'polish_translation' => 'required|string',
        ]);

        try {
            $verb = IrregularVerb::findOrFail($id);
            $verb->update($request->all());

            return response()->json([
                'message' => 'Wpis został z powodzeniem poprawiony w bazie!',
                'verb' => $verb,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Wystąpił błąd podczas aktualizacji wpisu: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $verb = IrregularVerb::findOrFail($id);
            $verb->delete();

            return redirect()->route('irregular-verbs.index')->with('success', 'Wpis został z powodzeniem usunięty z bazy!');
        } catch (\Exception $e) {
            return redirect()->route('irregular-verbs.index')->with('error', 'Wystąpił błąd podczas usuwania wpisu: ' . $e->getMessage());
        }
    }
}
