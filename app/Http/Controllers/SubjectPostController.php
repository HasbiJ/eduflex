<?php

namespace App\Http\Controllers;

use App\Models\SubjectPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectPostController extends Controller
{
    // Simpan postingan baru (news / module)
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'type'       => 'required|in:news,module',
            'title'      => 'required',
            'content'    => 'nullable',
        ]);

        SubjectPost::create([
            'subject_id' => $request->subject_id,
            'user_id'    => Auth::id(),
            'type'       => $request->type,
            'title'      => $request->title,
            // pakai input('content') karena nama "content" bentrok dengan properti bawaan Request
            'content'    => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Postingan berhasil dibuat');
    }

    // Simpan perubahan postingan
    public function update(Request $request, $id)
    {
        $request->validate([
            'type'      => 'required|in:news,module',
            'title'     => 'required',
            'content'   => 'nullable',
        ]);

        $post = SubjectPost::findOrFail($id);
        $post->type      = $request->type;
        $post->title     = $request->title;
        $post->content   = $request->input('content');
        $post->save();

        return redirect()->back()->with('success', 'Postingan berhasil diperbarui');
    }

    // Hapus postingan
    public function destroy($id)
    {
        SubjectPost::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Postingan berhasil dihapus');
    }
}
