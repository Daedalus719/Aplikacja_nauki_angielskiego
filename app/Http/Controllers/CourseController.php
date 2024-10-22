<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Test;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('course.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load('words');
        return view('course.show', compact('course'));
    }

    public function create()
    {
        return view('course.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        Test::create([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $course->id,
        ]);

        return redirect()->route('course.index')->with('success', 'Kurs został pomyślnie utworzony!');
    }

    public function edit(Course $course)
    {
        return response()->json($course);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $course->update($request->all());

        return redirect()->route('course.index')->with('success', 'Kurs został pomyślnie zaktualizowany!');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('course.index')->with('success', 'Kurs został pomyślnie usunięty.');
    }
}
