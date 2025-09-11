@extends('layouts.app')

@section('content')
<h1>Edit User</h1>
<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    Name: <input type="text" name="name" value="{{ $user->name }}" required><br>
    Email: <input type="email" name="email" value="{{ $user->email }}" required><br>
    Password: <input type="password" name="password" placeholder="Leave blank to keep current"><br>
    Branch: 
    <select name="branch_id" required>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}" {{ $branch->id == $user->branch_id ? 'selected' : '' }}>{{ $branch->name }}</option>
        @endforeach
    </select><br>
    Role: <input type="text" name="role" value="{{ $user->role }}" required><br>
    <button type="submit">Update</button>
</form>
@endsection
