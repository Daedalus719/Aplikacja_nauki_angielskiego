<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with('course')->get();
        return view('tests.index', compact('tests'));
    }

    public function show(Test $test)
    {
        $words = $test->course->words;
        return view('tests.show', compact('test', 'words'));
    }

    public function create()
    {
        return view('tests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        Test::create($request->all());

        return redirect()->route('tests.index');
    }

    public function edit(Test $test)
    {
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $test->update($request->all());

        return redirect()->route('tests.index');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('tests.index');
    }
}
