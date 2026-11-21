<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningVideo;

class LearningVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = auth()->user()
            ->LearningVideos()
            ->latest()
            ->paginate(10);

            return view('learning-videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('learning-videos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
        ]);

        auth()->user()->learningVideos()->create($validated);

        return redirect()
            ->route('learning-videos.index')
            ->with('success', 'Video berhasil ditambahkan!');
    }
    
    public function edit($id)
    {
        $video = auth()->user()
        ->learningVideos()
        ->findOrFail($id);

        return view('learning-videos.edit', compact('video'));
    }

    public function update (Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
        ]);

        $video = auth()->user()
        ->learningVideos()
        ->findOrFail($id);

        $video->update($validated);

        return redirect()
        ->route('learning-videos.index')
        ->with('success', 'Video berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $video = auth()->user()
        ->learningVideos()
        ->findOrFail($id);

        $video->delete();

    return redirect()
    ->route('learning-videos.index')
    ->with('success', 'Video berhasil dihapus!');
    }
}
