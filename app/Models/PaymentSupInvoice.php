<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSupInvoice extends Model
{
    use HasFactory;

    protected $table = 'tbl_payment_invoice_sup';

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'amount',
        'created_at',
        'updated_at'
    ];
    public function paymentsup()
    {
        return $this->belongsTo(PaymentSup::class, 'payment_id');
    }
    

}
