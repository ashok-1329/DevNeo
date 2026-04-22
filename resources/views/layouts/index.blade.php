@extends('layouts.admin')

@section('content')

<h3>Users</h3>

<a href="{{ route('users.create') }}" class="btn btn-success mb-3">Add User</a>

<table class="table table-bordered" id="usersTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role->name ?? '' }}</td>
            <td>
                <a href="{{ route('users.edit',$user->id) }}" class="btn btn-primary btn-sm">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection