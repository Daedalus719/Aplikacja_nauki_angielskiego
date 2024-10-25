<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Section;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($section_id)
    {
        try {
            $section = Section::findOrFail($section_id);
            $tasks = Task::where('section_id', $section_id)->get();

            return view('tasks.index', compact('tasks', 'section'));
        } catch (\Exception $e) {
            return redirect()->route('tasks.index', $section_id)->with('error', 'Wystąpił błąd podczas ładowania zadań: ' . $e->getMessage());
        }
    }

    public function create($section_id)
    {
        try {
            $section = Section::findOrFail($section_id);
            return view('tasks.create', compact('section'));
        } catch (\Exception $e) {
            return redirect()->route('tasks.index', $section_id)->with('error', 'Wystąpił błąd podczas ładowania sekcji: ' . $e->getMessage());
        }
    }

    public function store(Request $request, $section_id)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'task_type' => 'required|in:1,2',
        ]);

        try {
            Task::create([
                'section_id' => $section_id,
                'text' => $validated['text'],
                'task_type' => $validated['task_type'],
            ]);

            return redirect()->route('tasks.index', $section_id)->with('success', 'Zadanie dodane pomyślnie.');
        } catch (\Exception $e) {
            return redirect()->route('tasks.index', $section_id)->with('error', 'Wystąpił błąd podczas dodawania zadania: ' . $e->getMessage());
        }
    }

    public function randomTask($section_id, Request $request)
    {
        try {
            $taskType = $request->query('type');
            $task = Task::where('section_id', $section_id)->where('task_type', $taskType)->inRandomOrder()->first();

            if ($task) {
                return response()->json($task);
            }

            return response()->json(['message' => 'No tasks found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Wystąpił błąd podczas pobierania losowego zadania: ' . $e->getMessage()], 500);
        }
    }

    public function showAll($section_id)
    {
        try {
            $section = Section::findOrFail($section_id);
            $tasks = Task::where('section_id', $section_id)->get();

            return view('tasks.show_all', compact('tasks', 'section'));
        } catch (\Exception $e) {
            return redirect()->route('tasks.index', $section_id)->with('error', 'Wystąpił błąd podczas ładowania wszystkich zadań: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $task_id)
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        try {
            $task = Task::findOrFail($task_id);
            $task->text = $validated['text'];
            $task->save();

            return response()->json(['message' => 'Zadanie zostało pomyślnie zaktualizowane!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Wystąpił błąd podczas aktualizacji zadania: ' . $e->getMessage()], 500);
        }
    }

    public function delete($task_id)
    {
        try {
            $task = Task::findOrFail($task_id);
            $task->delete();

            return response()->json(['success' => true, 'message' => 'Zadanie zostało pomyślnie usunięte!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Wystąpił błąd podczas usuwania zadania: ' . $e->getMessage()], 500);
        }
    }
}
