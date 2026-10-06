<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil data user beserta relasi permissions
        return User::with('permissions')->orderBy('name', 'asc')->get();
    }

    /**
     * Header Tabel Excel
     */
    public function headings(): array
    {
        return [
            ['LAPORAN DATA PENGGUNA SISTEM - MUSICMEN STORE'], // Baris Judul Utama
            ['Tanggal Cetak: ' . date('d F Y H:i')],           // Baris Sub-judul
            [],                                                // Baris Kosong
            [                                                  // Header Tabel (Baris 4)
                'Nama Pengguna',
                'Alamat Email',
                'Level/Role',
                'Hak Akses (Permissions)',
                'Tanggal Akun Dibuat',
            ]
        ];
    }

    /**
     * Pemetaan data per baris
     */
    public function map($user): array
    {
        // Logika penggabungan permissions
        $permissions = $user->permissions->pluck('permission_key')->implode(', ');
        
        if ($user->role === 'admin') {
            $permissions = 'FULL ACCESS';
        }
        
        return [
            $user->name,
            $user->email,
            strtoupper($user->role),
            $permissions ?: 'NO PERMISSIONS SET',
            $user->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Styling Tabel (Warna, Border, Alignment)
     */
    public function styles(Worksheet $sheet)
    {
        // Gabungkan cell untuk judul laporan agar berada di tengah (A sampai E)
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');

        return [
            // Style untuk Judul Utama (Baris 1)
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            
            // Style untuk Tanggal Cetak (Baris 2)
            2 => [
                'font' => ['italic' => true, 'size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],

            // Style untuk Header Kolom (Baris 4)
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E78'] // Biru Tua Profesional
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],

            // Berikan Border ke seluruh data dari baris 4 sampai baris terakhir
            'A4:E' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
        ];
    }
}