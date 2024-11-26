<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'tbl_invoice';

    protected $fillable = [
        'no_invoice', 'tanggal_invoice','tanggal_buat', 'pembeli_id', 'metode_pengiriman', 'alamat',
        'matauang_id', 'rate_matauang', 'total_harga', 'total_bayar', 'wa_status',
        'status_bayar', 'status_id', 'alamat_id', 'pembeli_id','ratevolume_id','rateberat_id'
    ];

    public $timestamps = true;

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'alamat_id', 'id');
    }
}
