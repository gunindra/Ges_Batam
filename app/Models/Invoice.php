<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'tbl_invoice';

    protected $fillable = [
        'no_invoice', 'tanggal_invoice', 'pembeli_id', 'metode_pengiriman', 'alamat',
        'matauang_id', 'rate_matauang', 'total_harga', 'total_bayar', 'wa_status',
        'status_bayar', 'status_id'
    ];

    public $timestamps = true;
}
