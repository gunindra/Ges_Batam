<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalItem extends Model
{
    use HasFactory;

    protected $table = 'tbl_jurnal_items';

    // Jika Anda memiliki kolom yang bisa diisi secara massal
    protected $fillable = [
        'jurnal_id',
        'code_account',
        'description',
        'debit',
        'credit',
        'memo',
    ];

    // Relasi ke Jurnal
    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class, 'jurnal_id');
    }

    public function coa()
    {
        return $this->belongsTo(COA::class, 'code_account', 'id');
    }
}
