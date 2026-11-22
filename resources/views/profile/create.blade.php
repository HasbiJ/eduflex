@extends('layouts.app')

@section('title', 'Tambah Profil')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-4">Tambah Profil Guru</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.store') }}" method="POST" class="bg-white p-6 rounded-xl border space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold">NIP *</label>
            <input type="text" name="nip" value="{{ old('nip') }}" required class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-semibold">Mata Pelajaran *</label>
            <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-semibold">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-semibold">Alamat</label>
            <input type="text" name="address" value="{{ old('address') }}" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-semibold">Tanggal Lahir</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-semibold">Agama</label>
            <input type="text" name="religion" value="{{ old('religion') }}" class="w-full border p-2 rounded">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('profile.index') }}" class="bg-gray-200 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection