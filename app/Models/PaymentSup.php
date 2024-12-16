<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSup extends Model
{
    use HasFactory;

    protected $table = 'tbl_payment_sup';

    protected $fillable = [
        'kode_pembayaran',
        'invoice_id',
        'payment_date',
        'amount',
        'payment_method_id',
        'Keterangan',
        'created_at',
        'updated_at'
    ];

    // Relasi dengan tabel tbl_sup_invoice
    public function invoice()
    {
        return $this->belongsTo(SupInvoice::class, 'invoice_id');
    }

    // Relasi dengan tabel tbl_coa (metode pembayaran)
    public function paymentMethod()
    {
        return $this->belongsTo(COA::class, 'payment_method_id');
    }
    public function paymentInvoicesSup()
    {
        return $this->hasMany(PaymentSupInvoice::class, 'payment_id');
    }
}
