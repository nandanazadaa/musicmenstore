<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $token = 'DnNgXrU4jMBGAHoZiYKA';

    public function sendMessage($target, $message)
    {
        // Hapus semua karakter non-angka
        $target = preg_replace('/[^0-9]/', '', $target);

        // Jika nomor diawali 0, ubah ke 62
        if (str_starts_with($target, '0')) {
            $target = '62' . substr($target, 1);
        }
        // Jika nomor pendek (langsung angka 8...), tambahkan 62
        elseif (str_starts_with($target, '8')) {
            $target = '62' . $target;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
                'delay' => '2',
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $this->token"
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);
        Log::info("Fonnte Out: " . $response . " to " . $target);
        curl_close($curl);

        return $response;
    }
}
