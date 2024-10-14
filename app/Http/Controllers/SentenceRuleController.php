<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SentenceRule;

class SentenceRuleController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('sentence-rules.index', compact('sections'));
    }

    public function show($sectionId)
    {
        $section = Section::find($sectionId);
        if ($section) {
            $rules = $section->sentenceRules()->get();
            return response()->json([
                'section' => $section,
                'rules' => $rules,
            ]);
        }
        return response()->json(['error' => 'Section not found'], 404);
    }



    public function storeSection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $section = Section::create([
            'title' => $validatedData['title']
        ]);

        return response()->json($section);
    }


    public function addPage()
    {
        $sections = Section::all();
        return view('sentence-rules.addPage');
    }


    public function storeRule(Request $request)
    {
        $validatedData = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'rule_text' => 'required|string|max:1000',
        ]);

        $rule = SentenceRule::create([
            'section_id' => $validatedData['section_id'],
            'rule_text' => $validatedData['rule_text'],
        ]);

        return response()->json(['success' => true, 'rule' => $rule]);
    }



}
