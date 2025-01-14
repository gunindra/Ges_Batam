<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'tbl_company';

    protected $fillable = [
        'name',
    ];

    public function pembeli()
    {
        return $this->hasMany(Customer::class, 'company_id');
    }
}
