<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupInvoice extends Model
{
    use HasFactory;

    protected $table = 'tbl_sup_invoice';

    protected $fillable = [
        'invoice_no',
        'tanggal',
        'vendor',
        'matauang_id',
        'created_at',
        'updated_at',
    ];

    // Relasi dengan model TblSupInvoiceItems
    public function items()
    {
        return $this->hasMany(SupInvoiceItem::class, 'invoice_id');
    }
}
