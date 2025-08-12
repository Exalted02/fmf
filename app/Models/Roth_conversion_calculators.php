<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roth_conversion_calculators extends Model
{
    use HasFactory;
	protected $table = 'roth_conversion_calculators';
	protected $fillable = [
        'sl_no',
        'user_id',
		'conversion_start_age',
		'conversion_finish_age',
		'conversion_annual_fee',
		'rmd_start_age',
		'rmd_finish_age',
		'rmd_tax_free_income',
		'status', 
    ];
}
