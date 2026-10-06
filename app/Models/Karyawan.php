<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $fillable = [
        'nip',
        'nama',
        'email',
        'telepon',
        'alamat',
        'jabatan',
        'tanggal_masuk',
        'status',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    /**
     * Relasi ke absensi
     */
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Cek apakah karyawan sudah absen hari ini
     */
    public function sudahAbsenHariIni(): bool
    {
        return $this->absensis()
            ->whereDate('tanggal', today())
            ->exists();
    }
}
