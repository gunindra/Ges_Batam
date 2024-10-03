<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'tbl_jurnal';

    // Jika Anda memiliki kolom yang bisa diisi secara massal
    protected $fillable = [
        'tanggal',
        'tipe_kode',
        'no_journal',
        'no_ref',
        'status',
        'description',
    ];

    // Relasi ke JurnalItem
    public function items()
    {
        return $this->hasMany(JurnalItem::class, 'jurnal_id');
    }
}
