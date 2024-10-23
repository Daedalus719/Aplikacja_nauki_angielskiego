<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Section;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($section_id)
    {
        $section = Section::findOrFail($section_id);
        $tasks = Task::where('section_id', $section_id)->get();

        return view('tasks.index', compact('tasks', 'section'));
    }

    public function create($section_id)
    {
        $section = Section::findOrFail($section_id);
        return view('tasks.create', compact('section'));
    }

    public function store(Request $request, $section_id)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'task_type' => 'required|in:1,2',
        ]);

        Task::create([
            'section_id' => $section_id,
            'text' => $validated['text'],
            'task_type' => $validated['task_type'],
        ]);

        return redirect()->route('tasks.index', $section_id)->with('success', 'Zadanie dodane pomyślnie.');
    }

    public function randomTask($section_id, Request $request)
    {
        $taskType = $request->query('type');

        $task = Task::where('section_id', $section_id)->where('task_type', $taskType)->inRandomOrder()->first();

        if ($task) {
            return response()->json($task);
        }

        return response()->json(['message' => 'No tasks found'], 404);
    }

    public function showAll($section_id)
    {
        $section = Section::findOrFail($section_id);
        $tasks = Task::where('section_id', $section_id)->get();

        return view('tasks.show_all', compact('tasks', 'section'));
    }


    public function update(Request $request, $task_id)
    {
        $task = Task::findOrFail($task_id);
        $task->text = $request->text;
        $task->save();

        return response()->json(['message' => 'Zadanie zostało pomyślnie zaktualizowane!']);
    }


    public function delete($task_id)
    {
        $task = Task::findOrFail($task_id);
        $task->delete();

        return response()->json(['success' => true]);
    }
}
