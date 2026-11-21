@extends('layouts.app')

@section('title', 'Edit Video')

@section('content')
<div class="max-w-3xl mx-auto px-5 py-10">

    <h1 class="text-3xl font-bold mb-6">
        Edit Video Pembelajaran
    </h1>

    <form action="{{ route('learning-videos.update', $video->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Judul Video</label>
            <input
                type="text"
                name="title"
                value="{{ $video->title }}"
            >
        </div>

        <div class="mb-4">
            <label>Deskripsi</label>
            <textarea name="description">{{ $video->description }}</textarea>
        </div>

        <div class="mb-4">
            <label>URL Video YouTube</label>
            <input
                type="text"
                name="video_url"
                value="{{ $video->video_url }}"
            >
        </div>

        <button type="submit">Update Video</button>

    </form>

</div>
@endsection