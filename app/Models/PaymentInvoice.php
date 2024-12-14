<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    use HasFactory;

    protected $table = 'tbl_payment_invoice';

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'kuota',
        'amount',
    ];

    /**
     * Relasi ke tabel Payment.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Relasi ke tabel Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
