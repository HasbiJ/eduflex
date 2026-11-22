@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-4">Profil Guru</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($teacher)
        {{-- READ: Jika data profil sudah ada --}}
        <div class="bg-white p-6 rounded-xl border space-y-3">
            <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>NIP:</strong> {{ $teacher->nip }}</p>
            <p><strong>Mata Pelajaran:</strong> {{ $teacher->subject }}</p>
            <p><strong>No. HP:</strong> {{ $teacher->phone ?? '-' }}</p>
            <p><strong>Alamat:</strong> {{ $teacher->address ?? '-' }}</p>
            <p><strong>Tanggal Lahir:</strong> {{ $teacher->date_of_birth ?? '-' }}</p>
            <p><strong>Agama:</strong> {{ $teacher->religion ?? '-' }}</p>

            <div class="flex gap-3 pt-4">
                {{-- Tombol menuju halaman UPDATE --}}
                <a href="{{ route('profile.edit', $teacher->id) }}" class="bg-orange-500 text-white px-4 py-2 rounded">
                    Edit Profil
                </a>

                {{-- Form tombol DELETE --}}
                <form action="{{ route('profile.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data profil?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                        Hapus Profil
                    </button>
                </form>
            </div>
        </div>
    @else
        {{-- Jika data profil belum dibuat ($teacher == null) --}}
        <div class="bg-white p-6 rounded-xl border">
            <p class="mb-4 text-gray-600">Data profil guru belum diisi.</p>
            <a href="{{ route('profile.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded">
                + Lengkapi Profil
            </a>
        </div>
    @endif
</div>
@endsection