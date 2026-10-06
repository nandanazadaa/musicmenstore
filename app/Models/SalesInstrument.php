<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInstrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'nama_barang',
        'type',
        'sales',
        'jumlah_fee',
        'keterangan',
        'kondisi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        // REMOVED: 'jumlah_fee' => 'decimal:2', 
        // Karena jumlah_fee sekarang adalah JSON array, bukan single decimal
    ];

    /**
     * Get formatted fee - UPDATED untuk handle array fees
     */
    public function getFormattedFeeAttribute(): string
    {
        // Parse jumlah_fee sebagai array
        $fees = json_decode($this->jumlah_fee, true);
        
        if (!is_array($fees)) {
            $fees = [$this->jumlah_fee];
        }
        
        // Bersihkan dan hitung total
        $cleanFees = array_map(function($fee) {
            return (int)preg_replace('/[^0-9]/', '', (string)$fee);
        }, $fees);
        
        $total = array_sum($cleanFees);
        
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    /**
     * Get total fee as integer
     */
    public function getTotalFeeAttribute(): int
    {
        $fees = json_decode($this->jumlah_fee, true);
        
        if (!is_array($fees)) {
            $fees = [$this->jumlah_fee];
        }
        
        $cleanFees = array_map(function($fee) {
            return (int)preg_replace('/[^0-9]/', '', (string)$fee);
        }, $fees);
        
        return array_sum($cleanFees);
    }

    /**
     * Get sales list as array
     */
    public function getSalesListAttribute(): array
    {
        $sales = json_decode($this->sales, true);
        
        if (!is_array($sales)) {
            $sales = $this->sales ? explode(', ', $this->sales) : [];
        }
        
        return array_filter(array_map('trim', $sales));
    }

    /**
     * Get fees list as array (cleaned)
     */
    public function getFeesListAttribute(): array
    {
        $fees = json_decode($this->jumlah_fee, true);
        
        if (!is_array($fees)) {
            $fees = [$this->jumlah_fee];
        }
        
        return array_map(function($fee) {
            return (int)preg_replace('/[^0-9]/', '', (string)$fee);
        }, $fees);
    }

    /**
     * Get type name (formatted)
     */
    public function getTypeNameAttribute(): string
    {
        $types = [
            'electric' => 'Electric Guitar',
            'acoustic' => 'Acoustic Guitar',
            'bass' => 'Bass',
            'amplifier' => 'Amplifier',
            'effect' => 'Effect',
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get kondisi name (formatted)
     */
    public function getKondisiNameAttribute(): string
    {
        $kondisi = [
            'great' => 'Great',
            'good' => 'Good',
        ];

        return $kondisi[$this->kondisi] ?? ($this->kondisi ? ucfirst($this->kondisi) : '-');
    }
}