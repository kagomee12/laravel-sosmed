<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $posts = Post::with(['user', 'comments.replies.user'])->latest()->get();
        return view('dashboard', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Post::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Post berhasil dibuat.');
    }

    /**
     * Display the specified post.
     */
    public function show(Request $request, $id)
    {
        $post = Post::with(['user', 'comments.replies.user'])->findOrFail($id);
        return view('updatePost', compact('post'));
    }


    // public function edit($id)
    // {
    //     $post = Post::findOrFail($id);
    //     return view('dashboard', compact('post'));
    // }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($id);
        $post->update($validated);

        return redirect()->route('dashboard')->with('success', 'Post berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post berhasil dihapus.');
    }
}
