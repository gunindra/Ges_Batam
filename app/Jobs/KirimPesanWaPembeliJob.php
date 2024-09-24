<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\WhatsappTrait;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;

class KirimPesanWaPembeliJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WhatsappTrait;

    protected $invoiceId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::table('tbl_invoice')->where('id', $this->invoiceId)->update(['wa_status' => 'pending']);

            $invoice = DB::table('tbl_invoice as a')
                ->join('tbl_pembeli as b', 'a.pembeli_id', '=', 'b.id')
                ->join('tbl_status as d', 'a.status_id', '=', 'd.id')
                ->select('a.id', 'a.no_invoice', 'a.tanggal_invoice', 'b.nama_pembeli', 'a.alamat', 'a.metode_pengiriman', 'a.total_harga', 'a.matauang_id', 'a.rate_matauang', 'd.status_name', 'b.no_wa')
                ->where('a.id', $this->invoiceId)
                ->first();

            if (!$invoice) {
                throw new \Exception("Invoice tidak ditemukan");
            }

            // Ambil resi terkait invoice
            $resiData = DB::table('tbl_resi')
                ->where('invoice_id', $invoice->id)
                ->get(['no_resi', 'no_do', 'berat', 'panjang', 'lebar', 'tinggi']);

            if ($resiData->isEmpty()) {
                throw new \Exception("Tidak ada resi yang terkait dengan invoice ini");
            }

            // Tentukan pesan berdasarkan metode pengiriman
            if ($invoice->metode_pengiriman === 'Pickup') {
                $pesan = "*List barang* dengan no resi berikut telah siap untuk di pickup";
            } elseif ($invoice->metode_pengiriman === 'Delivery') {
                $pesan = "*List barang* dengan no resi berikut telah siap untuk diantarkan.";
            } else {
                throw new \Exception("Metode pengiriman tidak valid untuk invoice dengan ID $this->invoiceId");
            }

            // Membuat PDF dari data resi dan invoice
            try {
                Log::info('Memulai pembuatan PDF untuk invoice ID: ' . $invoice->id);

                $pdf = Pdf::loadView('exportPDF.notification', [
                    'resiData' => $resiData,
                    'invoice' => $invoice,
                ]);

                Log::info('Berhasil membuat PDF untuk invoice ID: ' . $invoice->id);

            } catch (\Exception $e) {
                Log::error('Error generating PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to generate PDF'], 500);
            }

            // Menyimpan PDF ke folder storage
            try {
                $pdfFileName = 'list_barang_'.$this->invoiceId.'_'.time().'.pdf';
                $filePath = storage_path('app/public/list_barang/' . $pdfFileName);
                $pdf->save($filePath);
            } catch (\Exception $e) {
                Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            // URL untuk file PDF
            $fileUrl = asset('storage/list_barang/' . $pdfFileName);
            $pesan .= "\n\n*Download List Barang Anda di sini:* $fileUrl";

            // Kirim pesan WhatsApp dengan PDF
            if ($invoice->no_wa) {
                // $this->kirimPesanWhatsapp($invoice->no_wa, $pesan);
                $this->kirimPesanWhatsapp($invoice->no_wa, $pesan, $fileUrl); // Kirim pesan dengan file PDF
                DB::table('tbl_invoice')->where('id', $this->invoiceId)->update(['wa_status' => 'sent']);
            } else {
                Log::warning("Nomor WhatsApp tidak ditemukan untuk pembeli dengan ID: " . $invoice->id);
            }

            // Hapus file PDF setelah pesan terkirim
            if (file_exists($filePath)) {
                unlink($filePath);
                Log::info('PDF berhasil dihapus: ' . $filePath);
            } else {
                Log::warning('PDF tidak ditemukan untuk dihapus: ' . $filePath);
            }
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());

            // Update invoice status to 'failed' if an error occurs
            DB::table('tbl_invoice')->where('id', $this->invoiceId)->update(['wa_status' => 'failed']);
        }
    }
}
