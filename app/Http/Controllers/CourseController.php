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

        try {
            $course = Course::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            Test::create([
                'title' => $request->title,
                'description' => $request->description,
                'course_id' => $course->id,
            ]);

            return redirect()->route('course.index')->with('success', 'Kurs "' . $course->title . '" został pomyślnie utworzony wraz z testem!');
        } catch (\Exception $e) {
            return redirect()->route('course.create')->with('error', 'Wystąpił błąd podczas tworzenia kursu: ' . $e->getMessage());
        }
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

        try {
            $course->update($request->all());

            return redirect()->route('course.index')->with('success', 'Kurs "' . $course->title . '" został pomyślnie zaktualizowany!');
        } catch (\Exception $e) {
            return redirect()->route('course.index')->with('error', 'Wystąpił błąd podczas aktualizacji kursu: ' . $e->getMessage());
        }
    }

    public function destroy(Course $course)
    {
        try {
            $courseTitle = $course->title;
            $course->delete();

            return redirect()->route('course.index')->with('success', 'Kurs "' . $courseTitle . '" został pomyślnie usunięty.');
        } catch (\Exception $e) {
            return redirect()->route('course.index')->with('error', 'Wystąpił błąd podczas usuwania kursu: ' . $e->getMessage());
        }
    }
}
