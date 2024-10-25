<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Rule;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        try {
            $sections = Section::with('rules')->get();
            return view('sections.index', compact('sections'));
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'Wystąpił błąd podczas ładowania sekcji: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            Section::create($validated);
            return redirect()->route('sections.index')->with('success', 'Sekcja została z powodzeniem stworzona!');
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'Wystąpił błąd podczas tworzenia sekcji: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $section = Section::with('rules')->findOrFail($id);
            return view('sections.show', compact('section'));
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'Wystąpił błąd podczas ładowania sekcji: ' . $e->getMessage());
        }
    }

    public function addRule(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        try {
            $section = Section::findOrFail($id);
            $section->rules()->create([
                'content' => $validated['content'],
            ]);

            return redirect()->route('sections.show', $id)->with('success', 'Reguła została z powodzeniem dodana!');
        } catch (\Exception $e) {
            return redirect()->route('sections.show', $id)->with('error', 'Wystąpił błąd podczas dodawania reguły: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            $section = Section::findOrFail($id);
            $section->update($validated);

            return redirect()->route('sections.index')->with('success', 'Sekcja została z powodzeniem zaktualizowana!');
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'Wystąpił błąd podczas aktualizacji sekcji: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $section = Section::findOrFail($id);
            $section->delete();

            return redirect()->route('sections.index')->with('success', 'Sekcja została z powodzeniem usunięta!');
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'Wystąpił błąd podczas usuwania sekcji: ' . $e->getMessage());
        }
    }

    public function updateRule(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $rule = Rule::findOrFail($id);
            $rule->content = $validated['content'];
            $rule->save();

            return response()->json([
                'success' => true,
                'message' => 'Reguła została zaktualizowana!',
                'content' => $rule->content,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas aktualizacji reguły: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteRule($id)
    {
        try {
            $rule = Rule::findOrFail($id);
            $rule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reguła została z powodzeniem usunięta!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas usuwania reguły: ' . $e->getMessage(),
            ], 500);
        }
    }
}
