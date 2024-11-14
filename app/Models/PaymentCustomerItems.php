<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCustomerItems extends Model
{
    use HasFactory;

    protected $table = 'tbl_payment_items';

    protected $fillable = [
        'invoice_id',
        'coa_id',
        'description',
        'debit',
        'credit',
        'memo',
    ];

    // Relasi dengan model TblSupInvoice
    public function invoice()
    {
        return $this->belongsTo(Payment::class, 'invoice_id');
    }

    // Relasi dengan model COA (tbl_coa)
    public function coa()
    {
        return $this->belongsTo(COA::class, 'coa_id');
    }
}
