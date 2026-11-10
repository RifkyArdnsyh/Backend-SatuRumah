<?php

namespace App\Policies;

use App\Models\Pengajuan;
use App\Models\User;

class PengajuanPolicy
{
    /**
     * Tentukan apakah user boleh melihat daftar pengajuan.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Tentukan apakah user boleh melihat detail pengajuan tertentu.
     */
    public function view(User $user, Pengajuan $pengajuan): bool
    {
        $role = strtolower($user->role ?? '');

        // Staf Disperwaskim dan Pimpinan boleh melihat semua permohonan
        if (in_array($role, ['admin', 'admin_disperwaskim', 'administrator', 'verifikator', 'perwaskim', 'tim_monitoring', 'pimpinan', 'kepala_dinas'], true)) {
            return true;
        }

        // Pengembang hanya boleh melihat miliknya sendiri (IDOR Prevention)
        return in_array($role, ['pengembang', 'developer'], true) && $pengajuan->user_id === $user->id;
    }

    /**
     * Tentukan apakah user boleh membuat pengajuan baru.
     */
    public function create(User $user): bool
    {
        $role = strtolower($user->role ?? '');
        return in_array($role, ['pengembang', 'developer', 'admin', 'admin_disperwaskim'], true);
    }

    /**
     * Tentukan apakah user boleh memperbarui pengajuan (misal melengkapi perbaikan).
     */
    public function update(User $user, Pengajuan $pengajuan): bool
    {
        $role = strtolower($user->role ?? '');

        if (in_array($role, ['admin', 'admin_disperwaskim', 'verifikator', 'pimpinan', 'kepala_dinas', 'perwaskim', 'tim_monitoring'], true)) {
            return true;
        }

        // Pengembang hanya boleh mengubah permohonan miliknya saat status Perlu perbaikan atau Menunggu verifikasi
        return in_array($role, ['pengembang', 'developer'], true) 
            && $pengajuan->user_id === $user->id
            && in_array($pengajuan->status, ['Perlu perbaikan', 'Menunggu verifikasi', 'Draft'], true);
    }

    /**
     * Tentukan apakah user boleh menghapus pengajuan.
     */
    public function delete(User $user, Pengajuan $pengajuan): bool
    {
        $role = strtolower($user->role ?? '');
        return in_array($role, ['admin', 'admin_disperwaskim'], true);
    }
}

