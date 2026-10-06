<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery; // Ubah ke FromQuery
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    protected $filters;

    // Terima filter dari Controller
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
    * Query data dengan filter
    */
    public function query()
    {
        $query = Product::query();

        // Filter Tanggal
        if (!empty($this->filters['date_start'])) {
            $query->whereDate('tanggal', '>=', $this->filters['date_start']);
        }
        if (!empty($this->filters['date_end'])) {
            $query->whereDate('tanggal', '<=', $this->filters['date_end']);
        }

        // Filter Tambahan
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('nomor_seri', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        return $query->orderBy('tanggal', 'desc');
    }

    /**
     * Judul Header Kolom (Disesuaikan dengan Excel Standar Musicmen)
     */
    public function headings(): array
    {
        return [
            ['LAPORAN DATA PRODUK - MUSICMEN STORE'],
            ['Periode: ' . ($this->filters['date_start'] ?? 'Awal') . ' s/d ' . ($this->filters['date_end'] ?? 'Sekarang')],
            ['Tanggal Cetak: ' . date('d F Y')],
            [],
            [                                         
                'Tanggal Masuk',
                'Nama Barang',
                'Nomor Seri',
                'Tempat Pembuatan',
                'Tahun',
                'Instrumen',
                'Warna',
                'Kelengkapan',
                'Kondisi',
                'Setup',
                'Hologram',
                'Cashier App',
                'Harga Beli',
            ]
        ];
    }

    /**
     * Pemetaan data (Native format agar bisa dijumlahkan di Excel)
     */
    public function map($product): array
    {
        // Bersihkan angka dari format "Rp" atau titik/koma agar menjadi numeric murni
        $hargaBeli = (float) preg_replace('/[^0-9]/', '', $product->harga_pembelian);

        return [
            $product->tanggal ? $product->tanggal->format('d/m/Y') : '-',
            $product->nama_barang,
            $product->nomor_seri ?? '-',
            $product->tempat_pembuatan ?? '-',
            $product->tahun_pembuatan ?? '-',
            ucfirst($product->type ?? '-'),
            $product->warna ?? '-',
            $product->kelengkapan ?? '-',
            $product->kondisi ?? '-',
            $product->setting_setup ?? 'Not Yet',
            $product->price_hologram ?? 'Not Yet',
            $product->input_cashier ?? 'Not Yet',
            $hargaBeli,
        ];
    }

    /**
     * Format Kolom (Mata Uang Rupiah untuk kolom M / Harga Beli)
     */
    public function columnFormats(): array
    {
        return [
            'M' => '"Rp "#,##0',
        ];
    }

    /**
     * Styling Tampilan
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Gabungkan cell Judul
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');

        return [
            1 => ['font' => ['bold' => true, 'size' => 16], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            2 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            3 => ['font' => ['italic' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            
            // Header Tabel (Baris 5)
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'B45309'] // Warna Amber/Coklat Emas khas Musicmen
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            
            // Border Otomatis
            'A5:M' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}