<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Current_financial_account extends Model
{
    use HasFactory;
	protected $table = 'current_financial_accounts';
	protected $fillable = [
        'sl_no',
        'user_id',
		'account_owner',
		'owner_name',
		'account_title',
		'rmd_start_age',
		'tax_qualification',
		'age_income_start',
		'account_value',
		'annual_income_value',
		'status', 
    ];
}
