<?php

namespace App\Observers;

use App\Models\ServiceHarian;
use App\Models\SalarySlip;
use App\Models\Staff;
use App\Models\SalesInstrument;

class ServiceHarianObserver
{
    /**
     * Handle the ServiceHarian "created" event.
     */
    public function created(ServiceHarian $serviceHarian): void
    {
        $this->updateSalarySlip($serviceHarian);
    }

    /**
     * Handle the ServiceHarian "updated" event.
     */
    public function updated(ServiceHarian $serviceHarian): void
    {
        $this->updateSalarySlip($serviceHarian);
    }

    /**
     * Handle the ServiceHarian "deleted" event.
     */
    public function deleted(ServiceHarian $serviceHarian): void
    {
        // When a service is deleted, we need to update the salary slip
        // by recalculating fees without the deleted service
        if (!$serviceHarian->eksekutor) {
            return;
        }

        $staff = \App\Models\Staff::where('nama', $serviceHarian->eksekutor)->first();
        if (!$staff) {
            return;
        }

        // Get the month from tanggal_masuk
        $month = date('Y-m', strtotime($serviceHarian->tanggal_masuk));

        // Find existing salary slip for this month and staff
        $salarySlip = \App\Models\SalarySlip::where('staff_id', $staff->id)
            ->where('month', $month)
            ->first();

        if ($salarySlip) {
            // Recalculate service fee for this month (without the deleted service)
            $serviceFee = \App\Models\ServiceHarian::where('eksekutor', $staff->nama)
                ->whereYear('tanggal_masuk', date('Y', strtotime($month . '-01')))
                ->whereMonth('tanggal_masuk', date('m', strtotime($month . '-01')))
                ->sum('fee_staff');

            // Recalculate sales fee for this month
            $salesFee = \App\Models\SalesInstrument::where('sales', $staff->nama)
                ->whereYear('tanggal', date('Y', strtotime($month . '-01')))
                ->whereMonth('tanggal', date('m', strtotime($month . '-01')))
                ->sum('jumlah_fee');

            // Recalculate total
            $total = $salarySlip->basic_salary
                + $salarySlip->transport_food_allowance
                + $salarySlip->overtime_fee
                + $salesFee
                + $serviceFee
                + $salarySlip->others_fee;

            // Update salary slip
            $salarySlip->update([
                'sales_fee' => $salesFee,
                'service_fee' => $serviceFee,
                'total' => $total,
            ]);
        }
    }

    /**
     * Update salary slip for the staff member based on service harian
     */
    private function updateSalarySlip(ServiceHarian $serviceHarian): void
    {
        if (!$serviceHarian->eksekutor) {
            return;
        }

        $staff = Staff::where('nama', $serviceHarian->eksekutor)->first();
        if (!$staff) {
            return;
        }

        // Get the month from tanggal_masuk
        $month = date('Y-m', strtotime($serviceHarian->tanggal_masuk));

        // Find existing salary slip for this month and staff
        $salarySlip = SalarySlip::where('staff_id', $staff->id)
            ->where('month', $month)
            ->first();

        if ($salarySlip) {
            // Recalculate service fee for this month
            $serviceFee = ServiceHarian::where('eksekutor', $staff->nama)
                ->whereYear('tanggal_masuk', date('Y', strtotime($month . '-01')))
                ->whereMonth('tanggal_masuk', date('m', strtotime($month . '-01')))
                ->sum('fee_staff');

            // Recalculate sales fee for this month
            $salesFee = SalesInstrument::where('sales', $staff->nama)
                ->whereYear('tanggal', date('Y', strtotime($month . '-01')))
                ->whereMonth('tanggal', date('m', strtotime($month . '-01')))
                ->sum('jumlah_fee');

            // Recalculate total
            $total = $salarySlip->basic_salary
                + $salarySlip->transport_food_allowance
                + $salarySlip->overtime_fee
                + $salesFee
                + $serviceFee
                + $salarySlip->others_fee;

            // Update salary slip
            $salarySlip->update([
                'sales_fee' => $salesFee,
                'service_fee' => $serviceFee,
                'total' => $total,
            ]);
        }
    }
}

