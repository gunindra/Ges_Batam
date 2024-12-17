<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAccount extends Model
{
    use HasFactory;
    protected $table = 'tbl_report_accounts';
    protected $fillable = ['coa_id'];

}