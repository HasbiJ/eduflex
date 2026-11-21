
@extends('layouts.app')

@section('title', 'Tambah Video')

@section('content')
<div class="max-w-3xl mx-auto px-5 py-10">
    <h1 class="text-3xl font-bold mb-6">Tambah Video Pembelajaran</h1>

    <form action="{{ route('learning-videos.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label>Judul Video</label>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description') }}</textarea>
            @error('description')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label>URL Video YouTube</label>
            <input type="text" name="video_url" value="{{ old('video_url') }}">
            @error('video_url')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Simpan Video</button>
    </form>
</div>
@endsection
