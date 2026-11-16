@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<div class="max-w-6xl mx-auto px-6 pt-10">

    {{-- Header: judul + tombol Add Class --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <p class="text-sm font-semibold">Subject</p>
            <p class="text-xl text-gray-700 mt-1 max-w-xl">
                Expand your knowledge with our curated library of high-quality educational videos led by expert instructors.
            </p>
        </div>
        <button type="button" onclick="openModal('modalCreate')"
                class="bg-orange-500 text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-orange-200 hover:bg-orange-600">
            + &nbsp; Add Class
        </button>
    </div>

    {{-- Pesan sukses & error --}}
    @if (session('success'))
        <div class="mt-4 text-sm bg-green-100 text-green-700 px-4 py-2 rounded-lg">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mt-4 text-sm bg-red-100 text-red-700 px-4 py-2 rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Search bar (memfilter kartu berdasarkan nama mapel / kelas) --}}
    <div class="max-w-3xl mx-auto mt-8 relative">
        <span class="absolute left-4 top-3 text-gray-400">🔍</span>
        <input type="text" id="searchInput" placeholder="Search for topics, teachers..."
               class="w-full bg-white border border-gray-300 rounded-xl pl-12 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
    </div>

    {{-- Grid kartu kelas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        @forelse ($subjects as $s)
            <div class="subject-card bg-white rounded-2xl shadow-lg overflow-hidden"
                 data-name="{{ strtolower($s->name . ' ' . $s->class_name) }}">

                {{-- Klik kartu -> halaman detail --}}
                <a href="{{ route('subjects.show', ['id' => $s->id]) }}" class="block">
                    <div class="relative h-36 flex items-center justify-center bg-gradient-to-br from-gray-900 via-purple-900 to-gray-800"
                         @if ($s->bannerUrl()) style="background-image:url('{{ $s->bannerUrl() }}');background-size:cover;background-position:center" @endif>
                        <div class="absolute inset-0 bg-black/30"></div>
                        <p class="relative text-white text-2xl font-extrabold tracking-widest text-center px-2">{{ strtoupper($s->name) }}</p>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-bold">{{ $s->name }}</h3>
                        <p class="text-xs text-gray-400">{{ $s->class_name }}</p>
                        <p class="text-sm text-gray-600 mt-2">{{ $s->user->name }}</p>

                    </div>
                </a>

                {{-- Tombol aksi cepat --}}
                <div class="px-5 pb-4 flex gap-2">
                    <button type="button"
                            onclick="openEditSubject({{ json_encode($s->only(['id', 'name', 'class_name', 'description'])) }})"
                            class="flex-1 text-xs font-bold bg-orange-100 text-orange-600 py-2 rounded-lg hover:bg-orange-200">Edit</button>
                    <form action="{{ route('subjects.destroy', $s->id) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Hapus kelas {{ $s->name }}? Semua data di dalamnya ikut terhapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-xs font-bold bg-red-100 text-red-600 py-2 rounded-lg hover:bg-red-200">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-400 py-16">Belum ada kelas. Klik "Add Class" untuk membuat kelas pertama.</p>
        @endforelse
    </div>
    <p id="noResult" class="hidden text-center text-gray-400 py-10">Kelas tidak ditemukan.</p>
</div>

{{-- ===== MODAL TAMBAH KELAS ===== --}}
<div id="modalCreate" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Add Class</h3>
        <form action="{{ route('subjects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-bold mb-1">Nama Mata Pelajaran</label>
                <input type="text" name="name" required placeholder="Contoh: Matematika"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Nama Kelas</label>
                <input type="text" name="class_name" required placeholder="Contoh: Class XI MIPA 1"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Gambar Banner (opsional)</label>
                <input type="file" name="banner_image" accept="image/*" class="text-sm">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modalCreate')" class="px-4 py-2 text-sm font-semibold rounded-lg bg-gray-100">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold rounded-lg bg-orange-500 text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT KELAS ===== --}}
<div id="modalEdit" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Edit Class</h3>
        <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-bold mb-1">Nama Mata Pelajaran</label>
                <input type="text" name="name" id="editName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Nama Kelas</label>
                <input type="text" name="class_name" id="editClassName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Deskripsi</label>
                <textarea name="description" id="editDescription" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Ganti Banner (opsional)</label>
                <input type="file" name="banner_image" accept="image/*" class="text-sm">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 text-sm font-semibold rounded-lg bg-gray-100">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold rounded-lg bg-orange-500 text-white">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Buka / tutup modal
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    // Isi form edit dengan data kelas yang dipilih
    function openEditSubject(data) {
        document.getElementById('formEdit').action = '{{ url('/subjects') }}/' + data.id;
        document.getElementById('editName').value = data.name;
        document.getElementById('editClassName').value = data.class_name;
        document.getElementById('editDescription').value = data.description ?? '';
        openModal('modalEdit');
    }

    // Search: sembunyikan kartu yang tidak cocok dengan kata kunci
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        let tampil = 0;
        document.querySelectorAll('.subject-card').forEach(function (card) {
            if (card.dataset.name.includes(keyword)) {
                card.style.display = '';
                tampil++;
            } else {
                card.style.display = 'none';
            }
        });
        document.getElementById('noResult').classList.toggle('hidden', tampil > 0);
    });
</script>
@endsection
