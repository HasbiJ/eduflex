@extends('layouts.app')

@section('title', 'Add Class')

@section('content')
<div class="max-w-lg mx-auto px-6 pt-10">
    <h1 class="text-2xl font-bold mb-4">Add Class</h1>

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="mb-4 text-sm bg-red-100 text-red-700 px-4 py-2 rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- enctype multipart wajib kalau form ada upload file --}}
    <form action="{{ route('subjects.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-bold mb-1">Nama Mata Pelajaran</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Nama Kelas</label>
            <input type="text" name="class_name" value="{{ old('class_name') }}" placeholder="Contoh: Class XI MIPA 1"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Gambar Banner (opsional)</label>
            <input type="file" name="banner_image" class="text-sm">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-orange-500 text-white font-bold px-6 py-2 rounded-lg">Simpan</button>
            <a href="{{ route('subjects.index') }}" class="bg-gray-100 font-semibold px-6 py-2 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection
