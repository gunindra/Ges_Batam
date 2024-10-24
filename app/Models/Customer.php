<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'tbl_pembeli';
    protected $fillable = [
        'marking',
        'nama_pembeli',
        'no_wa',
        'sisa_poin',
        'category_id',
        'transaksi_terakhir',
        'status',
        'non_active_at',
        'metode_pengiriman',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    protected $dates = [
        'transaksi_terakhir',
        'non_active_at',
    ];
}
