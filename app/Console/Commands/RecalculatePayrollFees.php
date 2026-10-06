<?php

namespace App\Console\Commands;

use App\Models\SalarySlip;
use App\Models\SalesInstrument;
use App\Models\Product;
use App\Models\ServiceHarian;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculatePayrollFees extends Command
{
    /**
     * php artisan payroll:recalculate-fees
     * php artisan payroll:recalculate-fees --month=2026-05
     * php artisan payroll:recalculate-fees --staff_id=12
     */
    protected $signature = 'payroll:recalculate-fees {--month=} {--staff_id=}';

    protected $description = 'Hitung ulang sales_fee & service_fee pada salary slip yang sudah tersimpan, dan update kolom total mengikuti perhitungan baru.';

    private function normalizeName(?string $name): string
    {
        return mb_strtolower(trim($name ?? ''));
    }

    private function calculateSalesInstrumentFee(string $staffName, Carbon $startDate, Carbon $endDate): int
    {
        $salesFee = 0;
        $cleanStaffName = $this->normalizeName($staffName);

        $salesInstruments = SalesInstrument::whereBetween('tanggal', [$startDate, $endDate])->get();
        foreach ($salesInstruments as $sale) {
            foreach ($sale->sales_list as $idx => $namaSales) {
                if ($this->normalizeName($namaSales) === $cleanStaffName && isset($sale->fees_list[$idx])) {
                    $salesFee += round((float) $sale->fees_list[$idx]);
                }
            }
        }

        return $salesFee;
    }

    public function handle()
    {
        $query = SalarySlip::with('staff');

        if ($month = $this->option('month')) {
            $query->where('month', $month);
        }

        if ($staffId = $this->option('staff_id')) {
            $query->where('staff_id', $staffId);
        }

        $slips = $query->get();

        if ($slips->isEmpty()) {
            $this->warn('Tidak ada slip gaji yang cocok dengan filter.');
            return self::SUCCESS;
        }

        $this->info("Menghitung ulang {$slips->count()} slip gaji...");
        $bar = $this->output->createProgressBar($slips->count());

        foreach ($slips as $slip) {
            $staff = $slip->staff;
            if (!$staff) {
                $bar->advance();
                continue;
            }

            $startDate = Carbon::parse($slip->month . '-01')->startOfMonth();
            $endDate   = Carbon::parse($slip->month . '-01')->endOfMonth();
            $cleanStaffName = $this->normalizeName($staff->nama);

            // --- Sales Fee dari SalesInstrument ---
            $salesFee = $this->calculateSalesInstrumentFee($staff->nama, $startDate, $endDate);

            // --- Product Fee dari additionalPics ---
            $productFee = 0;
            $products = Product::whereBetween('tanggal', [$startDate, $endDate])->with('additionalPics')->get();
            foreach ($products as $product) {
                foreach ($product->additionalPics as $pic) {
                    if ($this->normalizeName($pic->pic_name) === $cleanStaffName) {
                        $cleanFee = (int) preg_replace('/[^0-9]/', '', $pic->fee_amount ?? '0');
                        $productFee += $cleanFee;
                    }
                }
            }

            $totalSalesFeeCombined = $salesFee + $productFee;

            // --- Service Fee (Teknisi 30% + Penerima 5%) ---
            $feeSebagaiTeknisi = ServiceHarian::whereRaw('LOWER(TRIM(eksekutor)) = ?', [$cleanStaffName])
                ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                ->sum('fee_staff') ?? 0;

            $feeSebagaiPenerima = ServiceHarian::whereRaw('LOWER(TRIM(penerima)) = ?', [$cleanStaffName])
                ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                ->sum('fee_penerima') ?? 0;

            $serviceFee = round((float) ($feeSebagaiTeknisi + $feeSebagaiPenerima));

            // --- Hitung ulang total ---
            $newTotal = (float) $slip->basic_salary
                + (float) $slip->transport_food_allowance
                + (float) $slip->overtime_fee
                + $totalSalesFeeCombined
                + $serviceFee
                + (float) $slip->others_fee
                + (float) $slip->performance_incentive;

            $slip->update([
                'sales_fee'   => $totalSalesFeeCombined,
                'service_fee' => $serviceFee,
                'total'       => $newTotal,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Selesai. Semua slip gaji yang cocok sudah dihitung ulang.');

        return self::SUCCESS;
    }
}
