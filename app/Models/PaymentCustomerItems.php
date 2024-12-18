<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCustomerItems extends Model
{
    use HasFactory;
    protected $table = 'tbl_payment_items';
    protected $fillable = ['payment_id', 'coa_id', 'description', 'nominal', 'tipe', 'jurnal_item_id'];

    /**
     * Relationship with Payment.
     * A payment item belongs to a specific payment.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
