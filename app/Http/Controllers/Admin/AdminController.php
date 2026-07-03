<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ScanResult;
use App\Models\CommunityPost;

class AdminController extends Controller
{
    /**
     * Statistik sistem untuk dashboard admin.
     */
    public function stats(Request $request)
    {
        $totalUsers  = User::count();
        $totalScans  = ScanResult::count();
        $scansToday  = ScanResult::whereDate('created_at', today())->count();
        $totalPosts  = CommunityPost::count();

        $diseaseDistribution = ScanResult::selectRaw('disease_label as label, COUNT(*) as count')
            ->groupBy('disease_label')
            ->get();

        $scansPerDay = ScanResult::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Statistik berhasil diambil',
            'data'    => [
                'total_users'           => $totalUsers,
                'total_scans'           => $totalScans,
                'scans_today'           => $scansToday,
                'total_posts'           => $totalPosts,
                'disease_distribution'  => $diseaseDistribution,
                'scans_per_day'         => $scansPerDay,
            ],
        ], 200);
    }

    /**
     * Daftar semua user dengan jumlah scan masing-masing.
     */
    public function users(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'role', 'avatar', 'created_at')
            ->withCount('scanResults')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar user berhasil diambil',
            'data'    => $users,
        ], 200);
    }

    /**
     * Hapus user berdasarkan ID.
     */
    public function destroyUser(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        // Cegah admin menghapus dirinya sendiri
        if ($user->id === $request->user()->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak bisa menghapus akun sendiri',
                'data'    => null,
            ], 403);
        }

        // Hapus file avatar dari storage kalau ada
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Hapus file gambar scan dari storage
        $scanResults = ScanResult::where('user_id', $user->id)->get();
        foreach ($scanResults as $scan) {
            if ($scan->image_path) {
                Storage::disk('public')->delete($scan->image_path);
            }
        }

        // Hapus user (cascade akan menghapus scan_results, community_posts, comments)
        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil dihapus',
            'data'    => null,
        ], 200);
    }
}
