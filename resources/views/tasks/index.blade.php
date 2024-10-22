<x-app-layout>
    @section('title', 'Tasks for ' . $section->title)

    <div class="container">
        <h1>Zadania dla {{ $section->title }}</h1>

        @if (Auth::check() && (Auth::user()->usertype === 'Admin' || Auth::user()->usertype === 'Moderator'))
            <a href="{{ route('tasks.create', $section->id) }}" class="btn btn-primary">Dodaj zadanie</a>
        @endif
        @if ($tasks->count())
            <ul>
                @foreach ($tasks as $task)
                    <li>
                        {{ $task->text }}
                        @if (Auth::check() && (Auth::user()->usertype === 'Admin' || Auth::user()->usertype === 'Moderator'))
                            <a href="{{ route('tasks.edit', ['section_id' => $section->id, 'id' => $task->id]) }}"
                                class="btn btn-sm btn-primary">Edit</a>

                            <form
                                action="{{ route('tasks.destroy', ['section_id' => $section->id, 'id' => $task->id]) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p>Brak dostępnych zadań.</p>
        @endif
    </div>
</x-app-layout>
