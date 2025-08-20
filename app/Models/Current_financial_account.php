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
		'account_title',
		'tax_qualification',
		'account_value',
		'status', 
    ];
}
