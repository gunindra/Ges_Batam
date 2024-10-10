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
        'invoice_number',
        'payment_date',
        'amount',
        'payment_method',
    ];
}
