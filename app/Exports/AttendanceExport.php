<?php

namespace App\Exports;

use App\Models\StaffAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $query;

    public function __construct($query = null)
    {
        // Pastikan query mengambil relasi staff agar tidak error saat mapping
        $this->query = $query ?? StaffAttendance::with('staff')->orderBy('attendance_date', 'desc');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * Judul Header Kolom
     */
    public function headings(): array
    {
        return [
            ['LAPORAN ABSENSI STAFF - MUSICMEN STORE'], // Judul Utama
            ['Tanggal Cetak: ' . date('d F Y H:i')],    // Sub-judul
            [],                                         // Baris Kosong
            [                                           // Header Tabel (Baris 4)
                'No',
                'Tanggal',
                'ID Staff',
                'Nama Staff',
                'Check In',
                'Status',
                'Alamat/Lokasi Check In',
            ]
        ];
    }

    /**
     * Pemetaan Data (Mapping)
     */
    private $rowNumber = 0;

    public function map($attendance): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $attendance->attendance_date->format('d/m/Y'),
            $attendance->staff->id_employee ?? '-',
            $attendance->staff->nama ?? '-',
            $attendance->check_in_time ?? '--:--',
            $attendance->check_in_time ? 'HADIR' : 'ABSEN',
            $attendance->check_in_address ?? '-',
        ];
    }

    /**
     * Styling Excel (Warna, Font, Border)
     */
    public function styles(Worksheet $sheet)
    {
        // Gabungkan sel untuk judul utama
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');

        return [
            // Style Judul Utama
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Style Tanggal Cetak
            2 => [
                'font' => ['italic' => true, 'size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Style Header Tabel (Baris 4)
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
            // Border untuk seluruh tabel data
            'A4:H' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ],
        ];
    }
}