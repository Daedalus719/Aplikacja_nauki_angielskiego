<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Course;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        try {
            $courses = Course::all();
            return view('tests.index', compact('courses'));
        } catch (\Exception $e) {
            return redirect()->route('tests.index')->with('error', 'Wystąpił błąd podczas ładowania kursów: ' . $e->getMessage());
        }
    }

    public function show(Test $test)
    {
        try {
            $words = $test->course->words;
            return view('tests.show', compact('test', 'words'));
        } catch (\Exception $e) {
            return redirect()->route('tests.index')->with('error', 'Wystąpił błąd podczas ładowania testu: ' . $e->getMessage());
        }
    }

    public function destroy(Test $test)
    {
        try {
            $test->delete();
            return redirect()->route('tests.index')->with('success', 'Test został pomyślnie usunięty.');
        } catch (\Exception $e) {
            return redirect()->route('tests.index')->with('error', 'Wystąpił błąd podczas usuwania testu: ' . $e->getMessage());
        }
    }
}
