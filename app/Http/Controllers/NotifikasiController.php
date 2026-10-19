<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::with('pengajuan')->latest()->get();
        $unreadCount = Notifikasi::where('is_read', false)->count();
        $antreanCount = \App\Models\Pengajuan::count();

        return view('admin.notifikasi.index', compact('notifikasis', 'unreadCount', 'antreanCount'));
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->is_read = true;
        $notifikasi->save();

        if ($notifikasi->pengajuan_id) {
            return redirect()->route('pengajuan.show', ['id' => $notifikasi->pengajuan_id]);
        }

        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        Notifikasi::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi berhasil ditandai sudah dibaca.');
    }
}
