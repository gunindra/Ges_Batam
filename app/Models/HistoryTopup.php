<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTopup extends Model
{
    use HasFactory;

    protected $table = 'tbl_history_topup';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'topup_amount',
        'remaining_points',
        'price_per_kg',
        'account_id',
        'balance',
        'date',
        'expired_date',
        'code',
    ];

    // Relationship to the customer (tbl_pembeli)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    // Relationship to the price point
    public function pricePerKg()
    {
        return $this->belongsTo(PricePoin::class, 'price_per_kg_id');
    }

    // Relationship to the account (journal)
    public function account()
    {
        return $this->belongsTo(COA::class, 'account_id');
    }

    // Relationship to usage points
    public function usages()
    {
        return $this->hasMany(UsagePoints::class, 'history_topup_id');
    }
    protected $dates = ['expired_date'];
}
