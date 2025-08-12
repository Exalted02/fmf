<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roth_conversion_calculator_yearly_rule extends Model
{
    use HasFactory;
	protected $table = 'roth_conversion_calculator_yearly_rules';
	protected $fillable = [
        'roth_id',
        'investment_amount',
		'bonus',
		'assumed_return',
		'status', 
    ];
}
