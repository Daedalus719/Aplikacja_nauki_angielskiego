<x-app-layout>
    @section('title', 'Panel Admina')

    <div class="container mt-3">
        <h3 class="main-text">Zarejesrowani użytkownicy</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa Użytkownika</th>
                    <th>Email</th>
                    <th>Rola</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr data-id="{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>
                            <input type="text" value="{{ $user->name }}" class="form-control user-name" data-id="{{ $user->id }}" disabled>
                        </td>
                        <td>
                            <input type="text" value="{{ $user->email }}" class="form-control user-email" data-id="{{ $user->id }}" disabled>
                        </td>
                        <td>
                            <select class="form-control user-role" data-id="{{ $user->id }}" disabled>
                                <option value="User" {{ $user->usertype === 'User' ? 'selected' : '' }}>User</option>
                                <option value="Admin" {{ $user->usertype === 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Moderator" {{ $user->usertype === 'Moderator' ? 'selected' : '' }}>Moderator</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm edit-user" data-id="{{ $user->id }}">Edytuj</button>
                            <button class="btn btn-success btn-sm save-user d-none" data-id="{{ $user->id }}">Zapisz</button>
                            <button class="btn btn-secondary btn-sm cancel-edit d-none" data-id="{{ $user->id }}">Anuluj</button>
                            <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">Usuń</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @vite('resources/js/admin.js')
</x-app-layout>
