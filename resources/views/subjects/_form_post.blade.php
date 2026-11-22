{{-- Form tambah / edit postingan.
     Variabel yang dikirim: $subject, $post (null kalau tambah baru), $type (tipe awal) --}}
<form action="{{ $post ? route('posts.update', $post->id) : route('posts.store') }}" method="POST" class="space-y-3 text-sm">
    @csrf
    @if ($post)
        @method('PUT')
    @endif

    <input type="hidden" name="subject_id" value="{{ $subject->id }}">

    <div>
        <label class="block font-bold mb-1">Tipe</label>
        <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="news"   {{ ($post->type ?? $type) == 'news' ? 'selected' : '' }}>News (Pengumuman)</option>
            <option value="module" {{ ($post->type ?? $type) == 'module' ? 'selected' : '' }}>Module (Materi)</option>
        </select>
    </div>
    <div>
        <label class="block font-bold mb-1">Judul</label>
        <input type="text" name="title" value="{{ $post->title ?? '' }}" placeholder="Contoh: Pengumuman Minggu Ini"
               class="w-full border border-gray-300 rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block font-bold mb-1">Isi</label>
        <textarea name="content" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ $post->content ?? '' }}</textarea>
    </div>

    <button type="submit" class="bg-orange-500 text-white font-bold px-5 py-2 rounded-lg">Simpan</button>
</form>
