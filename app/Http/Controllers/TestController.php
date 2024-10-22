<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Course;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $courses = Course::all();

        return view('tests.index', compact('courses'));
    }

    public function show(Test $test)
    {
        $words = $test->course->words;
        return view('tests.show', compact('test', 'words'));
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('tests.index');
    }
}
