<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebitNote extends Model
{
    use HasFactory;

    protected $table = 'tbl_debit_note';

    protected $fillable = [
        'no_debitnote',
        'invoice_id',
        'account_id',
        'matauang_id',
        'note',
        'total_keseluruhan',
        'rate_currency'
    ];

    // Relasi ke tabel Invoice
    public function invoice()
    {
        return $this->belongsTo(SupInvoice::class, 'invoice_id');
    }

    // Relasi ke tabel COA
    public function coa()
    {
        return $this->belongsTo(COA::class, 'account_id');
    }

    // Relasi ke tabel Matauang
    public function matauang()
    {
        return $this->belongsTo(Matauang::class, 'matauang_id');
    }

    // Relasi ke DebitNoteItem
    public function items()
    {
        return $this->hasMany(DebitNoteItem::class, 'debit_note_id');
    }
}
