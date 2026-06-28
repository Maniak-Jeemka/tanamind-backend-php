<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\CommunityPost;

class CommentController extends Controller
{
    /**
     * Tambah komentar ke post komunitas.
     */
    public function store(Request $request, $id)
    {
        // Cek apakah post ada
        $post = CommunityPost::find($id);

        if (!$post) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Post tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        $validated = $request->validate([
            'body' => 'required|string|min:1|max:500',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'body'    => $validated['body'],
        ]);

        $comment->load('user:id,name,avatar');

        return response()->json([
            'status'  => 'success',
            'message' => 'Komentar berhasil ditambahkan',
            'data'    => $comment,
        ], 201);
    }
}
