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
        ]);

        Task::create([
            'section_id' => $section_id,
            'text' => $validated['text'],
        ]);

        return redirect()->route('tasks.index', $section_id)->with('success', 'Zadanie dodane pomyślnie.');
    }

    public function edit($section_id, $id)
    {
        $task = Task::where('section_id', $section_id)->where('id', $id)->firstOrFail();

        return view('tasks.edit', compact('task'));
    }


    public function update(Request $request, $section_id, $id)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $task = Task::where('section_id', $section_id)->where('id', $id)->firstOrFail();
        $task->text = $validated['text'];
        $task->save();

        return redirect()->route('tasks.index', ['section_id' => $section_id])
            ->with('success', 'Zadanie zostało pomyślnie zaktualizowane!');
    }


    public function destroy($section_id, $id)
    {
        $task = Task::where('section_id', $section_id)->where('id', $id)->firstOrFail();

        $task->delete();

        return redirect()->route('tasks.index', ['section_id' => $section_id])->with('success', 'Zadanie zostało pomyślnie usunięte!');
    }
}
