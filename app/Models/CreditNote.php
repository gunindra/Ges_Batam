<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    use HasFactory;

    protected $table = 'tbl_credit_note';

    protected $fillable = [
        'invoice_id',
        'account_id',
        'matauang_id',
        'rate_currency',
        'note',
        'total_keseluruhan'
    ];

    public function items()
    {
        return $this->hasMany(CreditNoteItem::class, 'credit_note_id');
    }
}

class CreditNoteItem extends Model
{
    use HasFactory;

    protected $table = 'tbl_credit_note_item';

    protected $fillable = [
        'credit_note_id',
        'no_resi',
        'deskripsi',
        'harga',
        'jumlah',
        'total'
    ];

    public function creditNote()
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_id');
    }
}
