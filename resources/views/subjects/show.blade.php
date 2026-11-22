@extends('layouts.app')

@section('title', $subject->name)

@section('content')

{{-- ===== SIDEBAR HOVER =====
     Normal: lebar w-16 (hanya ikon). Saat kursor diarahkan: lebar w-64 (teks muncul).
     Efek ini murni CSS Tailwind: "group" + "hover:" + "group-hover:" --}}
<aside class="group hidden md:block fixed left-0 top-[72px] bottom-0 z-40 w-16 hover:w-64 bg-white shadow hover:shadow-xl transition-all duration-300 overflow-hidden">

    <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50">
        <span class="w-8 text-xl text-center">🏠</span>
        <span class="whitespace-nowrap font-semibold text-sm opacity-0 group-hover:opacity-100">Home</span>
    </a>

    <div class="flex items-center gap-3 px-4 py-3">
        <span class="w-8 text-xl text-center">🎓</span>
        <span class="whitespace-nowrap font-semibold text-sm opacity-0 group-hover:opacity-100">Teaching</span>
    </div>

    {{-- Daftar semua kelas guru, badge huruf inisial warna-warni --}}
    @foreach ($subjects as $s)
        <a href="{{ route('subjects.show', $s->id) }}" class="flex items-center gap-3 px-4 py-2 hover:bg-orange-50">
            <span class="w-8 h-8 rounded-full text-white text-sm font-bold flex items-center justify-center {{ ['bg-orange-500', 'bg-purple-500', 'bg-teal-500'][$loop->index % 3] }}">
                {{ strtoupper(substr($s->name, 0, 1)) }}
            </span>
            <span class="whitespace-nowrap text-sm opacity-0 group-hover:opacity-100">{{ $s->name }} - {{ $s->class_name }}</span>
        </a>
    @endforeach

    <a href="{{ route('subjects.edit', $subject->id) }}" class="flex items-center gap-3 px-4 py-3 mt-2 hover:bg-orange-50">
        <span class="w-8 text-xl text-center">⚙️</span>
        <span class="whitespace-nowrap font-semibold text-sm opacity-0 group-hover:opacity-100">Settings</span>
    </a>
</aside>

<div class="md:pl-20">
<div class="max-w-6xl mx-auto px-6 pt-8">

    {{-- Pesan sukses & error --}}
    @if (session('success'))
        <div class="mb-4 text-sm bg-green-100 text-green-700 px-4 py-2 rounded-lg">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 text-sm bg-red-100 text-red-700 px-4 py-2 rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Banner kelas --}}
    <div class="relative rounded-2xl overflow-hidden h-64 md:h-80 bg-gray-800 flex flex-col justify-center p-8">
        @if ($subject->banner_image)
            <img src="{{ asset('storage/' . $subject->banner_image) }}" class="absolute inset-0 w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative text-white">
            <p class="text-sm">{{ $subject->user->name }}</p>
            <h1 class="text-4xl md:text-6xl font-extrabold">{{ strtoupper($subject->name) }}</h1>
            <p class="text-sm mt-1">{{ $subject->class_name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">

        {{-- Kolom kiri (25%): tombol pengumuman baru.
             <details> = bagian yang bisa dibuka-tutup (HTML bawaan, tanpa JavaScript) --}}
        <div>
            <details class="bg-white rounded-2xl border border-gray-200">
                <summary class="cursor-pointer list-none bg-orange-500 text-white font-bold text-sm text-center py-3 rounded-2xl">+ New Announcement</summary>
                <div class="p-4">
                    @include('subjects._form_post', ['post' => null, 'type' => 'news'])
                </div>
            </details>
        </div>

        {{-- Kolom kanan (75%): daftar pengumuman & materi --}}
        <div class="md:col-span-3 space-y-5">
            @forelse ($posts as $post)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">

                    <div class="p-5 flex gap-4">
                        <div class="w-14 h-14 shrink-0 rounded-full bg-orange-100 flex items-center justify-center text-2xl">
                            {{ $post->type == 'news' ? '📢' : '📖' }}
                        </div>
                        <div>
                            <p class="font-bold text-lg">
                                @if ($post->type == 'news')
                                    {{ $post->title }}
                                @else
                                    Module : {{ $post->title }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">{{ $post->created_at->format('F d Y') }}</p>
                            <p class="text-sm text-gray-700 mt-2 whitespace-pre-line">{{ $post->content }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $post->user->name }}</p>
                        </div>
                    </div>

                    {{-- Edit / Hapus --}}
                    <details class="px-5 pb-3 border-t border-gray-100">
                        <summary class="cursor-pointer text-right font-bold text-gray-500 pt-2">•••</summary>
                        <div class="mt-2 space-y-3">
                            @include('subjects._form_post', ['post' => $post, 'type' => $post->type])
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold bg-red-100 text-red-600 px-4 py-2 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </details>
                </div>
            @empty
                <p class="text-center text-gray-400 py-16">Belum ada pengumuman atau materi di kelas ini.</p>
            @endforelse
        </div>
    </div>

</div>
</div>
@endsection
