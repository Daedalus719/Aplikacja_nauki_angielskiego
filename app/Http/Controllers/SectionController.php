<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Rule;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('rules')->get();
        return view('sections.index', compact('sections'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Section::create($validated);

        return redirect()->route('sections.index')->with('success', 'Sekcja z powodzeniem została stworzona!');
    }

    public function show($id)
    {
        $section = Section::with('rules')->findOrFail($id);
        return view('sections.show', compact('section'));
    }

    public function addRule(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $section = Section::findOrFail($id);
        $section->rules()->create([
            'content' => $validated['content'],
        ]);

        return redirect()->route('sections.show', $id)->with('success', 'Reguła z powodzeniem została dodana!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $section = Section::findOrFail($id);
        $section->update($validated);

        return redirect()->route('sections.index')->with('success', 'Sekcja z powodzeniem została zaktualizowana!');
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->route('sections.index')->with('success', 'Sekcja z powodzeniem została usunięta!');
    }


    public function updateRule(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $rule = Rule::findOrFail($id);
        $rule->content = $validated['content'];
        $rule->save();

        return response()->json([
            'success' => true,
            'message' => 'Reguła została zaktualizowana!',
            'content' => $rule->content,
        ]);
    }

    public function deleteRule($id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reguła została z powodzeniem usunięta!'
        ]);
    }
}
