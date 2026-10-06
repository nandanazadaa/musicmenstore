<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceHarian extends Model
{
    protected $fillable = [
        'tanggal_masuk',
        'nama_customer',
        'whatsapp',
        'merk',
        'tipe',
        'instrumen',
        'jenis_service',
        'biaya_sparepart',
        'biaya_jasa',
        'fee_staff',    // Ini untuk Teknisi (30%)
        'fee_penerima', // Tambahkan ini (5%)
        'penerima',     // Tambahkan ini (Nama Frontdesk)
        'total_harga',
        'eksekutor',
        'status_pengerjaan',
        'tanggal_selesai',
        'keterangan',
        'status_pengambilan',
        'rincian_jasa',
        'rincian_sparepart',
    ];
    protected $casts = [
        'rincian_jasa' => 'array',
        'rincian_sparepart' => 'array',
    ];
}
