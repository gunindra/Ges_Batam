<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSupItem extends Model
{
    use HasFactory;

    // Tabel yang terkait dengan model ini
    protected $table = 'tbl_payment_sup_items';

    // Primary key (default 'id', tidak perlu didefinisikan ulang jika menggunakan konvensi Laravel)
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'payment_id',
        'coa_id',
        'description',
        'nominal',
        'tipeAccount', // Tambahkan kolom ini
    ];

    // Relasi ke model PaymentSup (assumed as 'tbl_payment_sup')
    public function payment()
    {
        return $this->belongsTo(PaymentSup::class, 'payment_id');
    }

    // Relasi ke model Coa (assumed as 'tbl_coa')
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}
