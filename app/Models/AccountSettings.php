<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSettings extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan konvensi penamaan Laravel
    protected $table = 'tbl_account_settings';

    // Tentukan field yang dapat diisi (fillable)
    protected $fillable = [
        'sales_account_id',
        'receivable_sales_account_id',
        'customer_sales_return_account_id',
        'discount_sales_account_id',
        'sales_profit_rate_account_id',
        'sales_loss_rate_account_id',
        'purchase_account_id',
        'debt_account_id',
        'supplier_purchase_return_account_id',
        'discount_purchase_account_id',
        'purchase_profit_rate_account_id',
        'purchase_loss_rate_account_id',
    ];

    // Jika ada relasi dengan model lain, definisikan di sini
    public function salesAccount()
    {
        return $this->belongsTo(COA::class, 'sales_account_id');
    }

    public function receivableSalesAccount()
    {
        return $this->belongsTo(COA::class, 'receivable_sales_account_id');
    }

    public function customerSalesReturnAccount()
    {
        return $this->belongsTo(COA::class, 'customer_sales_return_account_id');
    }

    public function discountSalesAccount()
    {
        return $this->belongsTo(COA::class, 'discount_sales_account_id');
    }

    public function salesProfitRateAccount()
    {
        return $this->belongsTo(COA::class, 'sales_profit_rate_account_id');
    }

    public function salesLossRateAccount()
    {
        return $this->belongsTo(COA::class, 'sales_loss_rate_account_id');
    }

    public function purchaseAccount()
    {
        return $this->belongsTo(COA::class, 'purchase_account_id');
    }

    public function debtAccount()
    {
        return $this->belongsTo(COA::class, 'debt_account_id');
    }

    public function supplierPurchaseReturnAccount()
    {
        return $this->belongsTo(COA::class, 'supplier_purchase_return_account_id');
    }

    public function discountPurchaseAccount()
    {
        return $this->belongsTo(COA::class, 'discount_purchase_account_id');
    }

    public function purchaseProfitRateAccount()
    {
        return $this->belongsTo(COA::class, 'purchase_profit_rate_account_id');
    }

    public function purchaseLossRateAccount()
    {
        return $this->belongsTo(COA::class, 'purchase_loss_rate_account_id');
    }
}
