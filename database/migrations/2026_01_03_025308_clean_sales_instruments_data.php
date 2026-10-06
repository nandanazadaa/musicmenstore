<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ambil semua data sales_instruments
        $sales = DB::table('sales_instruments')->get();
        
        foreach ($sales as $sale) {
            $cleanedData = [];
            
            // 1. Bersihkan field 'sales'
            $salesList = json_decode($sale->sales, true);
            if (!is_array($salesList)) {
                $salesList = $sale->sales ? explode(', ', $sale->sales) : [];
            }
            $salesList = array_filter(array_map('trim', $salesList));
            $cleanedData['sales'] = json_encode(array_values($salesList));
            
            // 2. Bersihkan field 'jumlah_fee' - INI YANG PALING PENTING
            $rawFees = $sale->jumlah_fee;
            $feesList = json_decode($rawFees, true);
            
            if (!is_array($feesList)) {
                if (strpos($rawFees, ',') !== false) {
                    $feesList = explode(',', $rawFees);
                } else {
                    $feesList = [$rawFees];
                }
            }
            
            // Bersihkan setiap fee dari format titik/koma
            $cleanFees = [];
            foreach ($feesList as $fee) {
                // Hapus semua karakter non-digit
                $cleaned = preg_replace('/[^0-9]/', '', (string)$fee);
                $cleanFees[] = $cleaned !== '' ? $cleaned : '0';
            }
            
            $cleanedData['jumlah_fee'] = json_encode($cleanFees);
            
            // Update record
            DB::table('sales_instruments')
                ->where('id', $sale->id)
                ->update($cleanedData);
        }
    }

    public function down()
    {
        // Tidak perlu rollback
    }
};