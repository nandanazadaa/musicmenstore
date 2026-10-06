<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'keterangan',
        'lokasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke karyawan
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Hitung durasi kerja (dalam menit)
     */
    public function getDurasiKerjaAttribute(): ?int
    {
        if ($this->jam_masuk && $this->jam_keluar) {
            $masuk = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_masuk);
            $keluar = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_keluar);
            return $masuk->diffInMinutes($keluar);
        }
        return null;
    }
}
