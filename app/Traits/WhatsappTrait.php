<?php

namespace App\Traits;

use DB;
use Illuminate\Support\Facades\Http;
use Log;

trait WhatsappTrait
{
    public function kirimPesanWhatsapp($noWa, $message, $fileUrl = null)
    {
        $url = $fileUrl ? 'https://wa.aplikasiajp.com/send-media' : 'https://wa.aplikasiajp.com/send-message';

        $apiKey = config('app.whatsapp_api_key');

        $sender = '62' . DB::table('tbl_ptges')->value('phone');

        $data = [
            'api_key' => $apiKey,
            'sender' => $sender,
            'number' => $noWa
        ];

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
            $response = Http::timeout(60)->post($url, $data);

            if ($response->successful()) {
                Log::info('Pesan WhatsApp berhasil dikirim ke ' . $noWa);
                return true;
            } else {
                Log::error('WhatsApp API Error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim pesan WhatsApp: ' . $e->getMessage());
            return false;
        }
    }




}

