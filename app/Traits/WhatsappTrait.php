<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait WhatsappTrait
{
    public function kirimPesanWhatsapp($noWa, $message)
    {
        $response = Http::post('https://wa.aplikasiajp.com/send-message', [
            'api_key' => 'qpWaNfN8vSQ7I8m1JiqzqfyyLWG9uT',
            'sender' => '6285183058668',
            'number' => $noWa,
            'message' => $message
        ]);

        if ($response->successful()) {
            return true;
        } else {
            $errorMessage = $response->json()['msg'] ?? 'Gagal mengirim pesan WhatsApp';
            throw new \Exception($errorMessage);
        }
    }
}
