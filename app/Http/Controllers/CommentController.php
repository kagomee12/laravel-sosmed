<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment_text' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'comment_text' => $validated['comment_text'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function show(Request $request, $id)
    {
        $comments = Comment::with(['user', 'replies'])->find($id);

        if (!$comments) {
            return back()->with('error', 'Komentar tidak ditemukan.');
        }

        return view('comments', compact('comments'));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function storeReply(Request $request)
    {
        $validated = $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'comment_text' => 'required|string',
        ]);

        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user()->id,
            'parent_id' => $validated['comment_id'],
            'comment_text' => $validated['comment_text'],
        ]);

        return back()->with('success', 'Balasan berhasil ditambahkan.');
    }
}
