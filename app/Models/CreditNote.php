<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    use HasFactory;

    protected $table = 'tbl_credit_note';

    protected $fillable = [
        'no_creditnote',
        'invoice_id',
        'account_id',
        'matauang_id',
        'rate_currency',
        'note',
        'total_keseluruhan',
        'status_bayar',
        'company_id',
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
