<?php

namespace App\Exports;

use App\Models\SalesInstrument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesInstrumentExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SalesInstrument::orderBy('tanggal', 'desc')->get();
    }

    /**
     * Header tabel Excel
     */
    public function headings(): array
    {
        return [
            ['LAPORAN PENJUALAN INSTRUMEN - MUSICMEN STORE'], // Judul Utama
            ['Tanggal Cetak: ' . date('d F Y')],               // Sub-judul
            [],                                                // Baris Kosong
            [                                                  // Header Tabel (Baris 4)
                'Tanggal',
                'Nama Barang',
                'Type',
                'Sales',
                'Jumlah Fee',
                'Kondisi',
                'Keterangan',
            ]
        ];
    }

    /**
     * Pemetaan data (Tanpa number_format agar tetap berupa angka native Excel)
     */
    public function map($sale): array
    {
        return [
            $sale->tanggal->format('d/m/Y'),
            $sale->nama_barang,
            ucfirst($sale->type),
            $sale->sales,
            $sale->jumlah_fee ?? 0,
            $sale->kondisi ? ucfirst($sale->kondisi) : '-',
            $sale->keterangan ?? '-',
        ];
    }

    /**
     * Format Kolom (Mata Uang Rupiah untuk kolom E)
     */
    public function columnFormats(): array
    {
        return [
            'E' => '"Rp "#,##0',
        ];
    }

    /**
     * Styling Tabel (Warna, Border, Alignment)
     */
    public function styles(Worksheet $sheet)
    {
        // Gabungkan cell untuk Judul Utama dan Tanggal Cetak
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');

        return [
            // Style untuk Judul Utama
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Style untuk Tanggal Cetak
            2 => [
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Style untuk Heading Kolom (Baris 4)
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E78'] // Warna Biru Tua
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Berikan Border ke seluruh data dari baris 4 sampai terakhir
            'A4:G' . ($sheet->getHighestRow()) => [
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