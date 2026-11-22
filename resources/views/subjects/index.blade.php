@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<div class="max-w-6xl mx-auto px-6 pt-10">

    {{-- Judul + tombol Add Class --}}
    <div class="flex justify-between items-start gap-4">
        <div>
            <p class="text-sm font-semibold">Subject</p>
            <p class="text-xs text-gray-700 mt-1 max-w-xl">
                Expand your knowledge with our curated library of high-quality educational videos led by expert instructors.
            </p>
        </div>
        <a href="{{ route('subjects.create') }}"
           class="bg-orange-500 text-white font-bold px-8 py-3 rounded-xl shadow-lg hover:bg-orange-600">+ Add Class</a>
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="mt-4 text-sm bg-green-100 text-green-700 px-4 py-2 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Search: memfilter kartu berdasarkan nama mapel --}}
    <div class="max-w-3xl mx-auto mt-8">
        <input type="text" id="search" placeholder="🔍  Search for topics, teachers..."
               class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
    </div>

    {{-- Daftar kartu kelas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        @forelse ($subjects as $subject)
            <div class="kartu bg-white rounded-2xl shadow-lg overflow-hidden" data-nama="{{ strtolower($subject->name) }}">

                {{-- Klik kartu -> halaman detail --}}
                <a href="{{ route('subjects.show', $subject->id) }}" class="block">
                    <div class="relative h-36 bg-gray-800 flex items-center justify-center">
                        @if ($subject->banner_image)
                            <img src="{{ asset('storage/' . $subject->banner_image) }}" class="absolute inset-0 w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-black/30"></div>
                        <p class="relative text-white text-2xl font-extrabold tracking-widest">{{ strtoupper($subject->name) }}</p>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-bold">{{ $subject->name }}</h3>
                        <p class="text-xs text-gray-400">{{ $subject->class_name }}</p>
                        <p class="text-sm text-gray-600 mt-2">{{ $subject->user->name }}</p>
                    </div>
                </a>

                {{-- Tombol Edit & Hapus --}}
                <div class="px-5 pb-4 flex gap-2">
                    <a href="{{ route('subjects.edit', $subject->id) }}"
                       class="flex-1 text-center text-xs font-bold bg-orange-100 text-orange-600 py-2 rounded-lg">Edit</a>
                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Hapus kelas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-xs font-bold bg-red-100 text-red-600 py-2 rounded-lg">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-400 py-16">Belum ada kelas. Klik "Add Class" untuk membuat kelas.</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Saat mengetik, sembunyikan kartu yang namanya tidak cocok
    document.getElementById('search').addEventListener('keyup', function () {
        var kata = this.value.toLowerCase();
        document.querySelectorAll('.kartu').forEach(function (kartu) {
            kartu.style.display = kartu.dataset.nama.includes(kata) ? '' : 'none';
        });
    });
</script>
@endsection
