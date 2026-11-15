<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherProfileController extends Controller
{
    // READ: Tampilkan halaman profil guru yang sedang login
    public function index()
    {
        $user = Auth::user();

        // Otomatis membuat data default saat pertama kali buka profil (jika belum ada)
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nip'           => '8765432',
                'title_prefix'  => 'Bu',
                'subject'       => 'Matematika',
                'phone'         => '081234567',
                'address'       => 'Jln Terusan Buah Baru, Bojongsoang',
                'date_of_birth' => '1999-01-26',
                'religion'      => 'Islam',
            ]
        );

        return view('profile.index', compact('user', 'teacher'));
    }

    // CREATE: Menyimpan data profil guru baru (jika belum punya profil)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'           => 'required|string|unique:teachers,nip',
            'title_prefix'  => 'nullable|string|max:20',
            'subject'       => 'required|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'religion'      => 'nullable|string|max:50',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $validated['user_id'] = Auth::id();
        Teacher::create($validated);

        return redirect()->route('profile.index')->with('success', 'Data profil guru berhasil ditambahkan!');
    }

    // UPDATE: Memperbarui biodata guru & tabel users
    public function update(Request $request, $id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->findOrFail($id);
        $user = $teacher->user;

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'nip'           => 'required|string|unique:teachers,nip,' . $teacher->id,
            'title_prefix'  => 'nullable|string|max:20',
            'subject'       => 'required|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'religion'      => 'nullable|string|max:50',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
                Storage::disk('public')->delete($teacher->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Update tabel users (nama & email)
        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update tabel teachers
        $teacher->update([
            'nip'           => $validated['nip'],
            'title_prefix'  => $validated['title_prefix'] ?? 'Bu',
            'subject'       => $validated['subject'],
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'religion'      => $validated['religion'],
            'avatar'        => $validated['avatar'] ?? $teacher->avatar,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profil guru berhasil diperbarui!');
    }

    // UPDATE PASSWORD (untuk Tab Security)
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Memakai Hash::make() manual sesuai aturan User.php tim
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }

    // DELETE: Menghapus foto profil atau mereset biodata guru
    public function destroy($id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->findOrFail($id);

        if ($teacher->avatar && Storage::disk('public')->exists($teacher->avatar)) {
            Storage::disk('public')->delete($teacher->avatar);
        }

        $teacher->delete();

        return redirect()->route('profile.index')->with('success', 'Data profil guru berhasil dihapus/direset!');
    }
}