<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Log;

trait WhatsappTrait
{
    public function kirimPesanWhatsapp($noWa, $message, $fileUrl = null)
    {
        $url = $fileUrl ? 'https://wa.aplikasiajp.com/send-media' : 'https://wa.aplikasiajp.com/send-message';

        $data = [
            'api_key' => 'qpWaNfN8vSQ7I8m1JiqzqfyyLWG9uT',
            'sender' => '6285183058668',
            'number' => $noWa
        ];

        // Jika ada file PDF, masukkan teks ke dalam 'caption'
        if ($fileUrl) {
            $data['media_type'] = 'pdf';
            $data['caption'] = $message;
            $data['url'] = $fileUrl;
        } else {
            $data['message'] = $message;
        }

        Log::info('Mengirim pesan WhatsApp ke: ' . $noWa);
        Log::info('File URL: ' . $fileUrl);
        Log::info('API URL: ' . $url);
        Log::info('Data yang dikirim: ' . json_encode($data));

        try {
            $response = Http::timeout(30)->post($url, $data);

            if ($response->successful()) {
                Log::info('Pesan WhatsApp berhasil dikirim ke ' . $noWa);
                return true; // Mengembalikan true jika sukses
            } else {
                Log::error('WhatsApp API Error: ' . $response->body());
                return false; // Mengembalikan false jika gagal
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim pesan WhatsApp: ' . $e->getMessage());
            return false; // Mengembalikan false jika ada exception
        }
    }




}

