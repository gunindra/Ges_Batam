<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resi extends Model
{
    protected $table = 'tbl_resi';

    protected $fillable = [
        'invoice_id', 'no_resi', 'no_do', 'berat', 'panjang', 'lebar', 'tinggi', 'harga'
    ];

    public $timestamps = true;

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
