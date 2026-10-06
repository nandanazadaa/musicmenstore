<?php

namespace App\Exports;

use App\Models\DailySale;
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

class DailySalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DailySale::with('staff')->orderBy('sale_date', 'desc')->get();
    }

    /**
     * Header Tabel
     */
    public function headings(): array
    {
        return [
            ['LAPORAN PENJUALAN HARIAN - MUSICMEN STORE'], // Baris 1
            ['Tanggal Cetak: ' . date('d F Y H:i')],       // Baris 2
            [],                                            // Baris 3 (Kosong)
            [                                              // Baris 4 (Header Kolom)
                'Tanggal',
                'Staff',
                'Shift',
                'Offline Sales',
                'Online Sales',
                'Shopee',
                'Tokopedia',
                'Cash',
                'QRIS',
                'Transfer',
                'Total Income',
                'Expenses',
                'Cash Deposit',
            ]
        ];
    }

    /**
     * Pemetaan Data (Tanpa number_format agar bisa diformat Excel secara native)
     */
    public function map($sale): array
    {
        $totalIncome = ($sale->cash ?? 0) + ($sale->qris ?? 0) + ($sale->transfer ?? 0);
        
        return [
            $sale->sale_date->format('d/m/Y'),
            $sale->staff->nama ?? '-',
            ucfirst($sale->shift ?? '-'),
            $sale->total_offline_sales ?? 0,
            $sale->total_online_sales ?? 0,
            $sale->shopee_sales ?? 0,
            $sale->tokopedia_sales ?? 0,
            $sale->cash ?? 0,
            $sale->qris ?? 0,
            $sale->transfer ?? 0,
            $totalIncome,
            $sale->expenses ?? 0,
            $sale->total_cash_deposit ?? 0,
        ];
    }

    /**
     * Format Kolom (Mata Uang Rupiah untuk kolom D sampai M)
     */
    public function columnFormats(): array
    {
        return [
            'D' => '"Rp "#,##0',
            'E' => '"Rp "#,##0',
            'F' => '"Rp "#,##0',
            'G' => '"Rp "#,##0',
            'H' => '"Rp "#,##0',
            'I' => '"Rp "#,##0',
            'J' => '"Rp "#,##0',
            'K' => '"Rp "#,##0',
            'L' => '"Rp "#,##0',
            'M' => '"Rp "#,##0',
        ];
    }

    /**
     * Styling Tampilan (Warna, Border, Alignment)
     */
    public function styles(Worksheet $sheet)
    {
        // Merge cell untuk judul utama
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');

        return [
            // Baris Judul 1
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Baris Tanggal Cetak
            2 => [
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Header Tabel (Baris 4)
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E78'] // Biru Tua
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Memberikan Border ke seluruh baris data
            'A4:M' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}