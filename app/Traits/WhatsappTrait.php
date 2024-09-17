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
            // Kirim request ke API dan cek respons
            $response = Http::timeout(30)->post($url, $data);

            if ($response->successful()) {
                Log::info('Pesan WhatsApp berhasil dikirim ke ' . $noWa);
                return response()->json(['success' => true, 'message' => 'Pesan berhasil dikirim'], 200);
            } else {
                // Log error dari API jika pengiriman gagal
                Log::error('WhatsApp API Error: ' . $response->body());
                return response()->json(['success' => false, 'message' => 'Gagal mengirim pesan'], 500);
            }
        } catch (\Exception $e) {
            // Log error koneksi atau timeout
            Log::error('Error saat mengirim pesan WhatsApp: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error mengirim pesan'], 500);
        }
    }




}
