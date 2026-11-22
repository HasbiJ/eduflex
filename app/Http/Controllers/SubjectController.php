<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    // Tampilkan semua kelas milik guru yang sedang login
    public function index()
    {
        $subjects = Subject::where('user_id', Auth::id())->get();

        return view('subjects.index', compact('subjects'));
    }

    // Tampilkan form tambah kelas
    public function create()
    {
        return view('subjects.create');
    }

    // Simpan kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'class_name'   => 'required',
            'description'  => 'nullable',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        // upload gambar banner (kalau ada), disimpan di storage/app/public/banners
        $banner = null;
        if ($request->hasFile('banner_image')) {
            $banner = $request->file('banner_image')->store('banners', 'public');
        }

        Subject::create([
            'user_id'      => Auth::id(),
            'name'         => $request->name,
            'class_name'   => $request->class_name,
            'description'  => $request->description,
            'banner_image' => $banner,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    // Tampilkan halaman kelas (banner + pengumuman/materi)
    public function show($id)
    {
        $subject = Subject::findOrFail($id);

        // semua kelas guru (untuk sidebar)
        $subjects = Subject::where('user_id', Auth::id())->get();

        // semua postingan kelas ini, terbaru di atas
        $posts = SubjectPost::where('subject_id', $id)->latest()->get();

        return view('subjects.show', compact('subject', 'subjects', 'posts'));
    }

    // Tampilkan form edit kelas
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);

        return view('subjects.edit', compact('subject'));
    }

    // Simpan perubahan kelas
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required',
            'class_name'   => 'required',
            'description'  => 'nullable',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->name        = $request->name;
        $subject->class_name  = $request->class_name;
        $subject->description = $request->description;

        // ganti banner hanya kalau ada gambar baru
        if ($request->hasFile('banner_image')) {
            $subject->banner_image = $request->file('banner_image')->store('banners', 'public');
        }

        $subject->save();

        return redirect()->route('subjects.show', $id)->with('success', 'Kelas berhasil diperbarui');
    }

    // Hapus kelas (postingan ikut terhapus karena onDelete cascade)
    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();

        return redirect()->route('subjects.index')->with('success', 'Kelas berhasil dihapus');
    }
}
