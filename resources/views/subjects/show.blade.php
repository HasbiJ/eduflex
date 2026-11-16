@extends('layouts.app')

@section('title', $subject->name)

@section('content')

{{-- ==================================================
     HOVER SIDEBAR (ala Google Classroom)
     Normal: ramping (w-16, hanya ikon). Saat di-hover: melebar (w-64) + shadow-xl
     ================================================== --}}
@php
    $warna = ['bg-orange-500', 'bg-purple-500', 'bg-teal-500', 'bg-pink-500', 'bg-blue-500'];
@endphp
<aside class="group hidden md:block fixed left-0 top-[72px] bottom-0 z-40 w-16 hover:w-64 bg-white border-r border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden">
    <div class="py-4">

        {{-- Home --}}
        <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50">
            <span class="w-8 h-8 shrink-0 flex items-center justify-center text-xl">🏠</span>
            <span class="font-semibold text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">Home</span>
        </a>

        {{-- Teaching / daftar kelas --}}
        <div class="flex items-center gap-3 px-4 py-3">
            <span class="w-8 h-8 shrink-0 flex items-center justify-center text-xl">🎓</span>
            <span class="font-semibold text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">Teaching</span>
        </div>
        @foreach ($subjects as $s)
            <a href="{{ route('subjects.show', ['id' => $s->id]) }}"
               class="flex items-center gap-3 px-4 py-2 hover:bg-orange-50 {{ $s->id == $subject->id ? 'bg-orange-50' : '' }}">
                <span class="w-8 h-8 shrink-0 rounded-full {{ $warna[$loop->index % 5] }} text-white text-sm font-bold flex items-center justify-center">
                    {{ strtoupper(substr($s->name, 0, 1)) }}
                </span>
                <span class="text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    {{ $s->name }} <span class="text-xs text-gray-400">{{ $s->class_name }}</span>
                </span>
            </a>
        @endforeach

        {{-- Pengaturan kelas (buka modal edit) --}}
        <button type="button" onclick="openEditSubject()" class="w-full flex items-center gap-3 px-4 py-3 mt-2 hover:bg-orange-50 text-left">
            <span class="w-8 h-8 shrink-0 flex items-center justify-center text-xl">⚙️</span>
            <span class="font-semibold text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">Settings</span>
        </button>
    </div>
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

    {{-- Sub-navbar tab (garis bawah oranye pada tab aktif) --}}
    <div class="flex gap-8 md:gap-12 border-b border-gray-300 text-sm">
        @foreach (['stream' => 'Stream', 'classwork' => 'Classwork', 'people' => 'People'] as $key => $label)
            <a href="{{ route('subjects.show', ['id' => $subject->id, 'tab' => $key]) }}"
               class="pb-3 -mb-px {{ $tab == $key ? 'border-b-2 border-orange-500 text-orange-600 font-bold' : 'text-gray-600 hover:text-orange-500' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- ==================================================
         TAB 1 : STREAM
         ================================================== --}}
    @if ($tab == 'stream')

        {{-- Hero banner kelas --}}
        <div class="relative rounded-2xl overflow-hidden h-64 md:h-80 mt-8 flex flex-col justify-center p-8 bg-gradient-to-br from-gray-900 via-purple-900 to-gray-800"
             @if ($subject->bannerUrl()) style="background-image:url('{{ $subject->bannerUrl() }}');background-size:cover;background-position:center" @endif>
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative">
                <div class="flex items-center gap-2 text-white text-sm">
                    <span class="w-7 h-7 rounded-full bg-orange-500 font-bold flex items-center justify-center">{{ strtoupper(substr($subject->user->name, 0, 1)) }}</span>
                    {{ $subject->user->name }}
                </div>
                <h1 class="text-white text-4xl md:text-6xl font-extrabold mt-2">{{ strtoupper($subject->name) }}</h1>
                <p class="text-white/80 text-sm mt-1">{{ $subject->class_name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">

            {{-- Kolom kiri (25%) --}}
            <div class="md:col-span-1 space-y-4">

                {{-- Card class code --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold">Class Code</h3>
                    <div class="flex items-center justify-between mt-2">
                        <span id="classCode" class="text-xl font-extrabold text-orange-500">{{ $subject->class_code }}</span>
                        <button type="button" onclick="copyCode()" title="Copy" class="text-lg hover:scale-110">📋</button>
                    </div>
                    <form action="{{ route('subjects.resetCode', $subject->id) }}" method="POST" class="mt-3"
                          onsubmit="return confirm('Reset kode kelas? Kode lama tidak berlaku lagi.')">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-purple-600 hover:underline">Reset Kode</button>
                    </form>
                </div>

                {{-- Card deadlines --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold">Deadlines</h3>
                    @forelse ($deadlines as $d)
                        <div class="mt-2 text-xs">
                            <p class="font-semibold">{{ $d->title }}</p>
                            <p class="text-gray-400">Due {{ $d->deadline->format('d M Y') }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 mt-2">No assignments due soon</p>
                    @endforelse
                </div>

                {{-- Tombol new announcement --}}
                <button type="button" onclick="openPostModal('news', 'stream')"
                        class="w-full bg-orange-500 text-white font-bold text-sm py-3 rounded-xl shadow-lg shadow-orange-200 hover:bg-orange-600">
                    + New Announcement
                </button>
            </div>

            {{-- Kolom kanan (75%): feed --}}
            <div class="md:col-span-3 space-y-5">
                @forelse ($posts as $post)
                    @php
                        $ikon = ['news' => '📢', 'task' => '📋', 'quiz' => '❓', 'module' => '📖'][$post->type];
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="p-5 flex gap-4">
                            <div class="w-14 h-14 shrink-0 rounded-full bg-orange-100 flex items-center justify-center text-2xl">{{ $ikon }}</div>
                            <div class="flex-1">
                                <p class="font-bold text-lg">
                                    @if ($post->type == 'news') {{ $post->title }} @else {{ ucfirst($post->type) }} : {{ $post->title }} @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ $post->created_at->format('F d Y') }}</p>
                                @if ($post->type == 'news' && $post->content)
                                    <p class="text-sm text-gray-700 mt-2 whitespace-pre-line">{{ $post->content }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $post->user->name }}</p>
                                @endif
                            </div>

                            {{-- Menu titik tiga: Edit / Hapus --}}
                            <details class="relative self-start">
                                <summary class="cursor-pointer list-none font-bold text-gray-500 px-2">•••</summary>
                                <div class="absolute right-0 mt-1 w-28 bg-white border border-gray-200 rounded-lg shadow-lg z-10 text-sm">
                                    <button type="button"
                                            onclick="openPostModal('{{ $post->type }}', 'stream', {{ json_encode(['id' => $post->id, 'type' => $post->type, 'title' => $post->title, 'content' => $post->content, 'deadline' => $post->deadline ? $post->deadline->format('Y-m-d') : '', 'max_score' => $post->max_score]) }})"
                                            class="w-full text-left px-3 py-2 hover:bg-orange-50">Edit</button>
                                    <form action="{{ route('posts.destroy', [$subject->id, $post->id]) }}" method="POST"
                                          onsubmit="return confirm('Hapus postingan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tab" value="stream">
                                        <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </details>
                        </div>

                        {{-- Daftar komentar --}}
                        @foreach ($post->comments as $c)
                            <div class="px-5 pb-2 text-xs">
                                <span class="font-bold">{{ $c->user->name }}</span>
                                <span class="text-gray-700">{{ $c->comment }}</span>
                            </div>
                        @endforeach

                        {{-- Input komentar (tekan Enter untuk kirim) --}}
                        <form action="{{ route('comments.store', [$subject->id, $post->id]) }}" method="POST" class="border-t border-gray-200">
                            @csrf
                            <input type="text" name="comment" placeholder="Class Comments..." required
                                   class="w-full px-5 py-3 text-xs focus:outline-none">
                        </form>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-16">Belum ada aktivitas di kelas ini.</p>
                @endforelse
            </div>
        </div>
    @endif

    {{-- ==================================================
         TAB 2 : CLASSWORK
         ================================================== --}}
    @if ($tab == 'classwork')
        <div class="flex items-center justify-between mt-8">
            <h2 class="text-2xl font-bold">Classwork <span class="text-base font-normal text-gray-400">- {{ $subject->name }}</span></h2>

            {{-- Tombol + Create dengan pilihan tipe --}}
            <details class="relative">
                <summary class="cursor-pointer list-none bg-orange-500 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-lg shadow-orange-200">+ Create</summary>
                <div class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-10 text-sm">
                    <button type="button" onclick="openPostModal('task', 'classwork')" class="w-full text-left px-4 py-2 hover:bg-orange-50">📋 Task (Tugas)</button>
                    <button type="button" onclick="openPostModal('quiz', 'classwork')" class="w-full text-left px-4 py-2 hover:bg-orange-50">❓ Quiz (Kuis)</button>
                    <button type="button" onclick="openPostModal('module', 'classwork')" class="w-full text-left px-4 py-2 hover:bg-orange-50">📖 Module (Materi)</button>
                </div>
            </details>
        </div>

        <div class="space-y-4 mt-6">
            @forelse ($classwork as $cw)
                @php
                    $ikon = ['task' => '📋', 'quiz' => '❓', 'module' => '📖'][$cw->type];
                @endphp
                <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-start gap-4">
                    <div class="w-14 h-14 shrink-0 rounded-full bg-orange-100 flex items-center justify-center text-2xl">{{ $ikon }}</div>
                    <div class="flex-1">
                        <p class="font-bold text-lg">{{ $cw->title }}</p>
                        <span class="inline-block text-[11px] font-bold bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">{{ ucfirst($cw->type) }}</span>
                        <p class="text-xs text-gray-500 mt-1">
                            Posted {{ $cw->created_at->format('d M Y') }}
                            @if ($cw->deadline) &bull; Deadline {{ $cw->deadline->format('d M Y') }} @endif
                            &bull; Max score {{ $cw->max_score }}
                        </p>
                        @if ($cw->content)
                            <p class="text-sm text-gray-700 mt-2 whitespace-pre-line">{{ $cw->content }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="openPostModal('{{ $cw->type }}', 'classwork', {{ json_encode(['id' => $cw->id, 'type' => $cw->type, 'title' => $cw->title, 'content' => $cw->content, 'deadline' => $cw->deadline ? $cw->deadline->format('Y-m-d') : '', 'max_score' => $cw->max_score]) }})"
                                class="text-xs font-bold bg-orange-100 text-orange-600 px-3 py-1.5 rounded-lg hover:bg-orange-200">Edit</button>
                        <form action="{{ route('posts.destroy', [$subject->id, $cw->id]) }}" method="POST"
                              onsubmit="return confirm('Hapus classwork ini?')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="tab" value="classwork">
                            <button type="submit" class="text-xs font-bold bg-red-100 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-200">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-16">Belum ada classwork. Klik "+ Create" untuk membuat tugas, kuis, atau materi.</p>
            @endforelse
        </div>
    @endif

    {{-- ==================================================
         TAB 3 : PEOPLE (hanya daftar guru pengampu)
         ================================================== --}}
    @if ($tab == 'people')
        <div class="mt-8 max-w-3xl">
            <h2 class="text-2xl font-bold text-orange-600 border-b border-orange-300 pb-2">Teachers</h2>
            <div class="flex items-center gap-3 mt-4">
                <span class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-orange-400 text-white font-bold flex items-center justify-center">
                    {{ strtoupper(substr($subject->user->name, 0, 1)) }}
                </span>
                <div>
                    <p class="font-semibold text-sm">{{ $subject->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $subject->user->email }}</p>
                </div>
            </div>
        </div>
    @endif

</div>
</div>

{{-- ===== MODAL POSTINGAN (dipakai untuk tambah & edit: news, task, quiz, module) ===== --}}
<div id="modalPost" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h3 id="postModalTitle" class="text-xl font-bold mb-4">Create</h3>
        <form id="formPost" action="{{ route('posts.store', $subject->id) }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="_method" id="postMethod" value="POST">
            <input type="hidden" name="tab" id="postTab" value="stream">

            <div>
                <label class="block text-sm font-bold mb-1">Tipe</label>
                <select name="type" id="postType" onchange="toggleScoreFields()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="news">News (Pengumuman)</option>
                    <option value="task">Task (Tugas)</option>
                    <option value="quiz">Quiz (Kuis)</option>
                    <option value="module">Module (Materi)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Judul</label>
                <input type="text" name="title" id="postTitle" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Deskripsi / Instruksi</label>
                <textarea name="content" id="postContent" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div id="scoreFields" class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-bold mb-1">Deadline</label>
                    <input type="date" name="deadline" id="postDeadline" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Poin Maksimal</label>
                    <input type="number" name="max_score" id="postMaxScore" min="0" max="100" value="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modalPost')" class="px-4 py-2 text-sm font-semibold rounded-lg bg-gray-100">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold rounded-lg bg-orange-500 text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT KELAS (dari ikon Settings di sidebar) ===== --}}
<div id="modalEdit" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Edit Class</h3>
        <form action="{{ route('subjects.update', $subject->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-bold mb-1">Nama Mata Pelajaran</label>
                <input type="text" name="name" value="{{ $subject->name }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Nama Kelas</label>
                <input type="text" name="class_name" value="{{ $subject->class_name }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ $subject->description }}</textarea>
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

    // Modal edit kelas (data sudah terisi dari server)
    function openEditSubject() {
        openModal('modalEdit');
    }

    // Salin kode kelas ke clipboard + notifikasi alert
    function copyCode() {
        const kode = document.getElementById('classCode').innerText;
        navigator.clipboard.writeText(kode).then(function () {
            alert('Kode kelas disalin: ' + kode);
        });
    }

    // Deadline & poin maksimal disembunyikan untuk tipe News
    function toggleScoreFields() {
        const tipe = document.getElementById('postType').value;
        document.getElementById('scoreFields').classList.toggle('hidden', tipe === 'news');
    }

    // Buka modal postingan. Kalau 'data' diisi -> mode EDIT, kalau kosong -> mode TAMBAH
    function openPostModal(tipe, tab, data) {
        // tutup dropdown <details> yang masih terbuka
        document.querySelectorAll('details[open]').forEach(function (d) { d.removeAttribute('open'); });

        const form = document.getElementById('formPost');
        document.getElementById('postTab').value = tab;
        document.getElementById('postType').value = tipe;

        if (data) {
            // mode edit: arahkan form ke route update (PUT)
            form.action = '{{ url('/subjects/' . $subject->id . '/posts') }}/' + data.id;
            document.getElementById('postMethod').value = 'PUT';
            document.getElementById('postModalTitle').innerText = 'Edit';
            document.getElementById('postTitle').value = data.title;
            document.getElementById('postContent').value = data.content ?? '';
            document.getElementById('postDeadline').value = data.deadline;
            document.getElementById('postMaxScore').value = data.max_score;
        } else {
            // mode tambah: arahkan form ke route store (POST)
            form.action = '{{ route('posts.store', $subject->id) }}';
            document.getElementById('postMethod').value = 'POST';
            document.getElementById('postModalTitle').innerText = (tipe === 'news') ? 'New Announcement' : 'Create';
            document.getElementById('postTitle').value = (tipe === 'news') ? 'News' : '';
            document.getElementById('postContent').value = '';
            document.getElementById('postDeadline').value = '';
            document.getElementById('postMaxScore').value = 100;
        }

        toggleScoreFields();
        openModal('modalPost');
    }
</script>
@endsection
