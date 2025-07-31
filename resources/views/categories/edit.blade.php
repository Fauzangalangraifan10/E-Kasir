@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-green-700">Edit Kategori</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Kategori</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', $category->name) }}"
                   class="border border-gray-300 rounded p-2 w-full" 
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <a href="{{ route('categories.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" 
                    class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection
