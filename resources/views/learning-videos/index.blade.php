@extends('layouts.app')

@section('title', 'Learning Video')

@section('content')
<div class="max-w-[1600px] mx-auto px-5 lg:px-10 py-10">

    <h1 class="text-3xl font-extrabold mb-6">
        Learning Video
    </h1>

    @forelse ($videos as $video)

    <div class="bg-white rounded-2xl p-6 shadow mb-4">

    <h2 class="text-xl font-bold">
        {{$video->title}}
    </h2>

    <p class="text-gray-600 mt-2">
        {{ $video->description ?? 'Tidak ada deskripsi.'}}
    </p>

    <a href="{{ $video->video_url}}"
    target="_blank"
    class="text-orange-500 font-semibold mt-3 inline-block">
    Tonton Video
    </a>

    <a href="{{ route('learning-videos.edit', $video->id) }}"
        class="text-blue-500 font-semibold">
        Edit
    </a>

    <form action="{{ route('learning-videos.destroy', $video->id) }}"
    method="POST">

    @csrf
    @method('DELETE')

    <button type="submit">
        Hapus Video
    </button>

</form>

    </div>

    @empty

    <p class="text-gray-500">
        Belum ada video pembelajaran.
    </p>

    @endforelse
</div>
@endsection