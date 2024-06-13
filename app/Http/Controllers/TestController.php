<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Course $course)
    {
        return view('tests', compact('course'));
    }

    public function vowelTest(Course $course)
    {
        $words = $course->words()->inRandomOrder()->limit(5)->get();
        return view('tests.vowel', compact('course', 'words'));
    }

    public function translationTest(Course $course)
    {
        $words = $course->words()->inRandomOrder()->limit(5)->get();
        return view('tests.translation', compact('course', 'words'));
    }
}
