<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebitNoteItem extends Model
{
    use HasFactory;

    protected $table = 'tbl_debit_note_item';

    protected $fillable = [
        'debit_note_id',
        'no_resi',
        'deskripsi',
        'harga',
        'jumlah',
        'total'
    ];

    // Relasi ke DebitNote
    public function debitNote()
    {
        return $this->belongsTo(DebitNote::class, 'debit_note_id');
    }
}
