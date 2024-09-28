<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <h3>Registered Users</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
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
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm edit-user" data-id="{{ $user->id }}">Edit</button>
                            <button class="btn btn-success btn-sm save-user d-none" data-id="{{ $user->id }}">Save</button>
                            <button class="btn btn-secondary btn-sm cancel-edit d-none" data-id="{{ $user->id }}">Cancel</button>
                            <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @vite('resources/js/admin.js')
</x-app-layout>
