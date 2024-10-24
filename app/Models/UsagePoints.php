<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsagePoints extends Model
{
    use HasFactory;

    protected $table = 'tbl_usage_points';

    protected $fillable = [
        'customer_id',
        'history_topup_id',
        'used_points',
        'price_per_kg',
        'usage_date',
    ];

    // Relationship to the customer (if needed)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship to the top-up from which the points were used
    public function historyTopup()
    {
        return $this->belongsTo(HistoryTopup::class, 'history_topup_id');
    }
}
