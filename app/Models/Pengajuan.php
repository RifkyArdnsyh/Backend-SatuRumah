<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $attributes = [
        'tahap' => 'Dokumen',
        'status' => 'Menunggu verifikasi',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'nama_pengembang',
        'nama_perumahan',
        'lokasi',
        'jumlah_unit',
        'luas_kawasan',
        'penanggung_jawab',
        'catatan_status',
        'diajukan_pada',
    ];

    /**
     * Mutasi eksplisit status & tahap (mencegah Mass Assignment dari form input)
     */
    public function transitionTo(string $tahap, string $status, ?string $catatan = null): self
    {
        $this->tahap = $tahap;
        $this->status = $status;
        if ($catatan !== null) {
            $this->catatan_status = $catatan;
        }
        $this->save();

        return $this;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPengajuan::class, 'pengajuan_id', 'id');
    }

    public function survey()
    {
        return $this->hasMany(SurveyPengajuan::class, 'pengajuan_id', 'id');
    }

    public function activeSurvey()
    {
        return $this->hasOne(SurveyPengajuan::class, 'pengajuan_id', 'id')->where('status', 'Terjadwal')->latest();
    }

    public function monitoring()
    {
        return $this->hasOne(MonitoringPengajuan::class, 'pengajuan_id', 'id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatPengajuan::class, 'pengajuan_id', 'id')->latest();
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'pengajuan_id', 'id');
    }
}
