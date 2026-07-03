<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CommunityPost;
use App\Models\ScanResult;

class CommunityController extends Controller
{
    /**
     * Ambil semua post komunitas, terbaru dulu.
     */
    public function index(Request $request)
    {
        $posts = CommunityPost::orderBy('created_at', 'desc')
            ->with([
                'user:id,name,avatar,role',
                'scanResult:id,image_path,disease_label,disease_confidence,severity_label,severity_confidence',
                'comments' => function ($query) {
                    $query->orderBy('created_at', 'desc')
                        ->limit(3)
                        ->with('user:id,name,avatar');
                },
            ])
            ->withCount('comments')
            ->get();

        // Tambahkan image_url ke setiap scanResult
        $posts->each(function ($post) {
            if ($post->scanResult) {
                $post->scanResult->makeVisible(['image_url']);
            }
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Berhasil mengambil data komunitas',
            'data'    => $posts,
        ], 200);
    }

    /**
     * Buat post komunitas baru dari scan milik user yang login.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scan_result_id' => 'required|integer|exists:scan_results,id',
            'caption'        => 'nullable|string|max:1000',
        ]);

        // Cek apakah scan milik user yang login
        $scanResult = ScanResult::find($validated['scan_result_id']);

        if ($scanResult->user_id !== Auth::id()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kamu tidak bisa share scan milik orang lain',
                'data'    => null,
            ], 403);
        }

        // Cek apakah scan sudah pernah di-share
        if ($scanResult->is_shared) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Scan ini sudah pernah dibagikan',
                'data'    => null,
            ], 422);
        }

        // Buat post baru
        $post = CommunityPost::create([
            'user_id'        => Auth::id(),
            'scan_result_id' => $scanResult->id,
            'caption'        => $validated['caption'] ?? null,
        ]);

        // Update is_shared pada scan_result
        $scanResult->update(['is_shared' => true]);

        // Load relasi
        $post->load([
            'user:id,name,avatar,role',
            'scanResult:id,image_path,disease_label,disease_confidence,severity_label,severity_confidence',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Post berhasil dibuat',
            'data'    => $post,
        ], 201);
    }

    /**
     * Hapus post komunitas berdasarkan ID.
     */
    public function destroy(Request $request, $id)
    {
        $post = CommunityPost::find($id);

        if (! $post) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Post tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        // Otorisasi: hanya pemilik post atau admin yang boleh menghapus
        $user = $request->user();
        if ($post->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kamu tidak memiliki izin untuk menghapus post ini',
                'data'    => null,
            ], 403);
        }

        // Hapus gambar scan terkait dari storage kalau ada
        if ($post->scan_result_id) {
            $scanResult = ScanResult::find($post->scan_result_id);
            if ($scanResult) {
                if ($scanResult->image_path) {
                    Storage::disk('public')->delete($scanResult->image_path);
                }
                // Reset is_shared supaya scan bisa di-share ulang
                $scanResult->update(['is_shared' => false]);
            }
        }

        // Hapus post (komentar terhapus otomatis via cascade)
        $post->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Post berhasil dihapus',
            'data'    => null,
        ], 200);
    }
}
