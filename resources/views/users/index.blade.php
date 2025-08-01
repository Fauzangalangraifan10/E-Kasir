@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between">
        <span>Manajemen User</span>
        <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <!-- Edit -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Delete -->
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                       <!-- Aktivasi / Nonaktifkan -->
@if(auth()->user()->role === 'super_admin' || (auth()->user()->role === 'admin' && $user->role === 'kasir'))
@if($user->status === 'active')
    <form action="{{ route('users.deactivate', $user->id) }}" method="POST" style="display:inline">
        @csrf
        @method('PATCH')
        <button class="btn btn-warning btn-sm">Nonaktifkan</button>
    </form>
@else
    <form action="{{ route('users.activate', $user->id) }}" method="POST" style="display:inline">
        @csrf
        @method('PATCH')
        <button class="btn btn-success btn-sm">Aktifkan</button>
    </form>
@endif
@endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
