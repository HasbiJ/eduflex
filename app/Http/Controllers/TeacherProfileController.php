<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherProfileController extends Controller
{
    // READ: Menampilkan data profil guru
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        return view('profile.index', compact('teacher'));
    }

    // CREATE (Form): Menampilkan form tambah profil
    public function create()
    {
        return view('profile.create');
    }

    // CREATE (Proses): Menyimpan data baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nip'     => 'required|unique:teachers,nip',
            'subject' => 'required',
        ]);

        Teacher::create([
            'user_id'       => Auth::id(),
            'nip'           => $request->nip,
            'subject'       => $request->subject,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'religion'      => $request->religion,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil dibuat');
    }

    // UPDATE (Form): Menampilkan form edit profil
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('profile.edit', compact('teacher'));
    }

    // UPDATE (Proses): Menyimpan perubahan data
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'nip'     => 'required|unique:teachers,nip,' . $teacher->id,
            'subject' => 'required',
        ]);

        $teacher->update($request->only([
            'nip',
            'subject',
            'phone',
            'address',
            'date_of_birth',
            'religion',
        ]));

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diupdate');
    }

    // DELETE: Menghapus data profil
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil dihapus');
    }
}