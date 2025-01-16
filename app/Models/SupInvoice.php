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
        'vendor_id',
        'matauang_id',
        'no_ref',
        'rate_matauang',
        'status_bayar',
        'total_bayar',
        'total_harga',
        'created_at',
        'updated_at',
        'company_id',
    ];

    // Relasi dengan model TblSupInvoiceItems
    public function items()
    {
        return $this->hasMany(SupInvoiceItem::class, 'invoice_id');
    }

    // Relasi dengan model Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
