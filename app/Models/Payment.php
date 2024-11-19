<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'tbl_payment_customer';

    protected $fillable = [
        'kode_pembayaran',
        'pembeli_id',
        'payment_date',
        'discount',
        'payment_buat',
        'payment_method_id',
    ];

    /**
     * Relasi ke tabel pembeli.
     */
    public function pembeli()
    {
        return $this->belongsTo(Customer::class, 'pembeli_id');
    }

    /**
     * Relasi ke tabel COA (payment method).
     */
    public function paymentMethod()
    {
        return $this->belongsTo(COA::class, 'payment_method_id');
    }

    /**
     * Relasi ke tabel payment_invoice.
     */
    public function paymentInvoices()
    {
        return $this->hasMany(PaymentInvoice::class, 'payment_id');
    }

    public function paymentCustomerItems()
    {
        return $this->hasMany(PaymentCustomerItems::class, 'payment_id');
    }
}
