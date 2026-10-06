<?php

namespace App\Exports;

use App\Models\ServiceHarian;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{Alignment, Border, Fill};

class ServiceHarianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    public function collection()
    {
        return ServiceHarian::orderBy('tanggal_masuk', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN SERVICE HARIAN - MUSICMEN STORE'],
            ['Tanggal Cetak: ' . date('d F Y')],
            [],
            ['ID', 'Tanggal Masuk', 'Customer', 'WhatsApp', 'Merk/Tipe', 'Instrumen', 'Jenis Service', 'Sparepart', 'Biaya Jasa', 'Fee Staff (30%)', 'Total Harga', 'Eksekutor', 'Status', 'Pengambilan']
        ];
    }

    public function map($service): array
    {
        return [
            $service->id,
            \Carbon\Carbon::parse($service->tanggal_masuk)->format('d/m/Y'),
            $service->nama_customer,
            $service->whatsapp,
            $service->merk . ($service->tipe ? ' / ' . $service->tipe : ''),
            ucfirst($service->instrumen),
            $service->jenis_service,
            $service->biaya_sparepart ?? 0,
            $service->biaya_jasa ?? 0,
            $service->fee_staff ?? 0,
            $service->total_harga ?? 0,
            $service->eksekutor,
            strtoupper($service->status_pengerjaan),
            $service->status_pengambilan,
        ];
    }

    public function columnFormats(): array
    {
        return ['H' => '"Rp "#,##0', 'I' => '"Rp "#,##0', 'J' => '"Rp "#,##0', 'K' => '"Rp "#,##0'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:N1');
        $sheet->mergeCells('A2:N2');
        return [
            1 => ['font' => ['bold' => true, 'size' => 16], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E78']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A4:N' . $sheet->getHighestRow() => [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]
        ];
    }
}