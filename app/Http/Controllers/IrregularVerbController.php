<?php

namespace App\Http\Controllers;

use App\Models\IrregularVerb;
use Illuminate\Http\Request;

class IrregularVerbController extends Controller
{
    public function index()
    {
        $irregularVerbs = IrregularVerb::orderBy('verb_1st_form', 'asc')->get();
        return view('irregular-verbs.index', compact('irregularVerbs'));
    }

    public function showTaskPage()
    {
        $verbs = IrregularVerb::inRandomOrder()->limit(5)->get();

        return view('irregular-verbs.tasks', compact('verbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'verb_1st_form' => 'required|string|max:255',
            'verb_2nd_form' => 'required|string|max:255',
            'verb_3rd_form' => 'required|string|max:255',
            'polish_translation' => 'required|string|max:255',
        ]);

        IrregularVerb::create($request->all());

        return redirect()->route('irregular-verbs.index')->with('success', 'Wpis został z powodzeniem dodany do bazy!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'verb_1st_form' => 'required|string',
            'verb_2nd_form' => 'required|string',
            'verb_3rd_form' => 'required|string',
            'polish_translation' => 'required|string',
        ]);

        $verb = IrregularVerb::findOrFail($id);
        $verb->update($request->all());

        return response()->json([
            'message' => 'Wpis został z powodzeniem poprawiony w bazie!',
            'verb' => $verb,
        ]);
    }

    public function destroy($id)
    {
        $verb = IrregularVerb::findOrFail($id);
        $verb->delete();

        return response()->json(['message' => 'Wpis został z powodzeniem usunięty z bazy!']);
    }
}
