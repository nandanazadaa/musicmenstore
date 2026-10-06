<?php

namespace App\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpService
{
    /**
     * Send OTP to phone number
     */
    public function sendOtpEmail($email)
    {
        // Generate 6-digit OTP
        $otpCode = OtpVerification::generateCode(6);
        
        // Set expiry time (5 minutes)
        $expiresAt = Carbon::now()->addMinutes(5);
        
        // Invalidate previous OTPs for this phone
        OtpVerification::where('email', $email)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->update(['is_verified' => true]);
        
        // Create new OTP record
        $otp = OtpVerification::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => $expiresAt,
            'is_verified' => false,
            'attempts' => 0,
        ]);
        
        // TODO: Integrate with SMS Gateway (Twilio, Vonage, dll)
        // For now, log the OTP for testing purposes
        Log::info("OTP Code untuk {$email}: {$otpCode}");
        Log::info("OTP Expires at: {$expiresAt}");
        
        // Kirim via email
        $this->sendEmail($email, "Kode OTP MUSICMEN Anda: {$otpCode}. Berlaku 5 menit.");
        
        return $otp;
    }
    
    /**
     * Verify OTP code
     */
    public function verifyOtpEmail($email, $otpCode)
    {
        // Find the latest unverified OTP for this phone
        $otp = OtpVerification::where('email', $email)
            ->where('is_verified', false)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$otp) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak ditemukan. Silakan request OTP baru.',
            ];
        }
        
        // Check if expired
        if ($otp->expires_at->isPast()) {
            return [
                'success' => false,
                'message' => 'Kode OTP sudah kadaluarsa. Silakan request OTP baru.',
            ];
        }
        
        // Check attempts
        if ($otp->attempts >= 5) {
            return [
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Silakan request OTP baru.',
            ];
        }
        
        // Verify OTP code
        if ($otp->otp_code !== $otpCode) {
            // Increment attempts on wrong code
            $otp->incrementAttempts();
            
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid. Sisa percobaan: ' . (5 - $otp->attempts),
            ];
        }
        
        // Mark as verified
        $otp->markAsVerified();
        
        return [
            'success' => true,
            'message' => 'OTP berhasil diverifikasi.',
            'otp' => $otp,
        ];
    }
    
    /**
     * Send SMS via gateway (placeholder for future implementation)
     */
    private function sendSms($phone, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $from = env('TWILIO_FROM'); // contoh: +123456789 atau whatsapp:+123456789
        $useWhatsapp = env('OTP_VIA_WHATSAPP', false);

        if (!$sid || !$token || !$from) {
            Log::warning("TWILIO credential belum di-set. OTP tidak dikirim via SMS/WA.");
            return;
        }

        $to = $this->formatPhoneE164($phone, $useWhatsapp);

        try {
            $response = Http::withBasicAuth($sid, $token)->asForm()->post(
                "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json",
                [
                    'From' => $from,
                    'To' => $to,
                    'Body' => $message,
                ]
            );

            if ($response->failed()) {
                Log::error('Gagal kirim OTP via Twilio: ' . $response->body());
            } else {
                Log::info("OTP terkirim via Twilio ke {$to}");
            }
        } catch (\Exception $e) {
            Log::error('Exception kirim OTP via Twilio: ' . $e->getMessage());
        }
    }

    /**
     * Send OTP via email
     */
    private function sendEmail($email, $message)
    {
        try {
            Mail::raw($message, function ($mail) use ($email) {
                $mail->to($email)
                    ->subject('Kode OTP MUSICMEN');
            });
            Log::info("OTP terkirim via email ke {$email}");
        } catch (\Exception $e) {
            Log::error('Gagal kirim OTP via email: ' . $e->getMessage());
        }
    }

    /**
     * Format nomor ke E.164, opsi WhatsApp
     */
    private function formatPhoneE164($phone, $isWhatsapp = false)
    {
        // Hilangkan non-digit
        $digits = preg_replace('/\D+/', '', $phone);

        // Jika mulai 0, ubah ke +62 (Indonesia)
        if (strpos($digits, '0') === 0) {
            $digits = '62' . substr($digits, 1);
        } elseif (strpos($digits, '62') !== 0 && strpos($digits, '+62') !== 0 && strpos($digits, '1') !== 0 && strpos($digits, '+1') !== 0) {
            // fallback: tambah +
            // Anda bisa sesuaikan country code default
            $digits = '62' . $digits;
        }

        $e164 = '+' . ltrim($digits, '+');

        if ($isWhatsapp || (env('OTP_CHANNEL') === 'whatsapp')) {
            return 'whatsapp:' . $e164;
        }

        return $e164;
    }
}
