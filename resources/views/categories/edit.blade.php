@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success bg-opacity-75 text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-tags me-2"></i> Edit Kategori</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection