<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil data member beserta jumlah kunjungan
        return Member::withCount('visits')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Header Tabel Excel
     */
    public function headings(): array
    {
        return [
            ['LAPORAN DATA MEMBER - MUSICMEN STORE'], // Baris Judul Utama
            ['Tanggal Cetak: ' . date('d F Y H:i')],  // Baris Sub-judul
            [],                                       // Baris Kosong
            [                                         // Header Tabel (Baris 4)
                'ID Member',
                'Nama Lengkap',
                'Nomor HP',
                'Email',
                'Jumlah Kunjungan',
                'Status Membership',
                'Tanggal Bergabung',
            ]
        ];
    }

    /**
     * Pemetaan data per baris
     */
    public function map($member): array
    {
        return [
            $member->member_id,
            $member->name,
            $member->phone,
            $member->email ?? '-',
            $member->visits_count ?? 0,
            strtoupper($member->status ?? 'active'),
            $member->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Styling Tabel (Warna, Border, Alignment)
     */
    public function styles(Worksheet $sheet)
    {
        // Gabungkan cell untuk judul laporan agar berada di tengah
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');

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
                    'startColor' => ['rgb' => '1F4E78'] // Warna Biru Tua Profesional
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],

            // Berikan Border ke seluruh data dari baris 4 sampai baris terakhir
            'A4:G' . ($sheet->getHighestRow()) => [
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