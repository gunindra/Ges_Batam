<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'tbl_payment_customer';

    protected $fillable = [
        'invoice_id',
        'payment_date',
        'amount',
        'discount',
        'payment_method_id',
        'kode_pembayaran',
    ];

    public function paymentItems()
    {
        return $this->hasMany(PaymentCustomerItems::class, 'payment_id');
    }
}
