<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectComment;
use App\Models\SubjectPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    // ===== Fungsi bantu =====

    // Ambil kelas milik guru yang sedang login (kalau bukan miliknya -> 404)
    private function ownSubject($id)
    {
        return Subject::where('user_id', Auth::id())->findOrFail($id);
    }

    // Buat kode kelas acak, misal EDF-7X9K, dan pastikan belum dipakai
    private function makeClassCode()
    {
        do {
            $code = 'EDF-' . Str::upper(Str::random(4));
        } while (Subject::where('class_code', $code)->exists());

        return $code;
    }

    // Kembali ke halaman detail kelas pada tab tertentu
    private function backToTab($id, $tab, $message)
    {
        return redirect()->route('subjects.show', ['id' => $id, 'tab' => $tab])
            ->with('success', $message);
    }

    // ===== CRUD KELAS =====

    // Daftar semua kelas milik guru
    public function index()
    {
        $subjects = Subject::where('user_id', Auth::id())->latest()->get();

        return view('subjects.index', compact('subjects'));
    }

    // Simpan kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'class_name'   => 'required|string|max:255',
            'description'  => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload banner (kalau ada), disimpan di storage/app/public/banners
        $banner = null;
        if ($request->hasFile('banner_image')) {
            $banner = $request->file('banner_image')->store('banners', 'public');
        }

        Subject::create([
            'user_id'      => Auth::id(),
            'name'         => $request->name,
            'class_name'   => $request->class_name,
            'description'  => $request->description,
            'class_code'   => $this->makeClassCode(),
            'banner_image' => $banner,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Update data kelas
    public function update(Request $request, $id)
    {
        $subject = $this->ownSubject($id);

        $request->validate([
            'name'         => 'required|string|max:255',
            'class_name'   => 'required|string|max:255',
            'description'  => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'        => $request->name,
            'class_name'  => $request->class_name,
            'description' => $request->description,
        ];

        // Kalau upload banner baru, hapus banner lama
        if ($request->hasFile('banner_image')) {
            if ($subject->banner_image) {
                Storage::disk('public')->delete($subject->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        $subject->update($data);

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    // Hapus kelas (postingan dan komentar ikut terhapus karena onDelete cascade)
    public function destroy($id)
    {
        $subject = $this->ownSubject($id);

        if ($subject->banner_image) {
            Storage::disk('public')->delete($subject->banner_image);
        }
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Kelas berhasil dihapus.');
    }

    // Reset kode kelas
    public function resetCode($id)
    {
        $subject = $this->ownSubject($id);
        $subject->update(['class_code' => $this->makeClassCode()]);

        return $this->backToTab($id, 'stream', 'Kode kelas berhasil direset.');
    }

    // ===== DETAIL KELAS (4 TAB) =====

    public function show(Request $request, $id)
    {
        $subject = $this->ownSubject($id);

        // Tab aktif: stream / classwork / people
        $tab = $request->query('tab', 'stream');
        if (!in_array($tab, ['stream', 'classwork', 'people'])) {
            $tab = 'stream';
        }

        // Semua kelas guru (untuk hover sidebar)
        $subjects = Subject::where('user_id', Auth::id())->orderBy('name')->get();

        // Semua postingan kelas (terbaru di atas) + komentar
        $posts = $subject->posts()->with(['user', 'comments.user'])->latest()->get();

        // Deadline mendekat (task & quiz yang tenggatnya belum lewat)
        $deadlines = $subject->posts()
            ->whereIn('type', ['task', 'quiz'])
            ->whereNotNull('deadline')
            ->whereDate('deadline', '>=', today())
            ->orderBy('deadline')
            ->take(5)
            ->get();

        // Classwork = semua postingan selain news
        $classwork = $posts->where('type', '!=', 'news');

        return view('subjects.show', compact(
            'subject', 'subjects', 'tab', 'posts', 'deadlines', 'classwork'
        ));
    }

    // ===== STREAM & CLASSWORK (tabel subject_posts) =====

    public function storePost(Request $request, $id)
    {
        $subject = $this->ownSubject($id);

        $request->validate([
            'type'      => 'required|in:news,task,quiz,module',
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'deadline'  => 'nullable|date',
            'max_score' => 'nullable|integer|min:0|max:100',
        ]);

        SubjectPost::create([
            'subject_id' => $subject->id,
            'user_id'    => Auth::id(),
            'type'       => $request->type,
            'title'      => $request->title,
            'content'    => $request->input('content'),
            'deadline'   => $request->type == 'news' ? null : $request->deadline,
            'max_score'  => $request->max_score ?? 100,
        ]);

        return $this->backToTab($id, $request->input('tab', 'stream'), 'Postingan berhasil dibuat.');
    }

    public function updatePost(Request $request, $id, $postId)
    {
        $subject = $this->ownSubject($id);
        $post = $subject->posts()->findOrFail($postId);

        $request->validate([
            'type'      => 'required|in:news,task,quiz,module',
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'deadline'  => 'nullable|date',
            'max_score' => 'nullable|integer|min:0|max:100',
        ]);

        $post->update([
            'type'      => $request->type,
            'title'     => $request->title,
            'content'   => $request->input('content'),
            'deadline'  => $request->type == 'news' ? null : $request->deadline,
            'max_score' => $request->max_score ?? 100,
        ]);

        return $this->backToTab($id, $request->input('tab', 'stream'), 'Postingan berhasil diperbarui.');
    }

    public function destroyPost(Request $request, $id, $postId)
    {
        $subject = $this->ownSubject($id);
        $subject->posts()->findOrFail($postId)->delete();

        return $this->backToTab($id, $request->input('tab', 'stream'), 'Postingan berhasil dihapus.');
    }

    // ===== KOMENTAR =====

    public function storeComment(Request $request, $id, $postId)
    {
        $subject = $this->ownSubject($id);
        $post = $subject->posts()->findOrFail($postId);

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        SubjectComment::create([
            'subject_post_id' => $post->id,
            'user_id'         => Auth::id(),
            'comment'         => $request->comment,
        ]);

        return $this->backToTab($id, 'stream', 'Komentar terkirim.');
    }
}
