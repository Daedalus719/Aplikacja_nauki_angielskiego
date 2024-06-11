<?php
namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('course.courses', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load('words');
        return view('course.show', compact('course'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Course::create($request->all());

        return redirect()->route('courses');
    }
}
