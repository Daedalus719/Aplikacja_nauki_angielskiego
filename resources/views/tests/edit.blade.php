<x-app-layout>
    @section('title', 'Edytuj test')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('tests.update', $test) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $test->title }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-textarea mt-1 block w-full rounded-md shadow-sm" required>{{ $test->description }}</textarea>
                        </div>

                        <button type="submit" style="background-color: blue; color: white; padding: 10px; border-radius: 5px;">
                            Zaktualizuj test
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
