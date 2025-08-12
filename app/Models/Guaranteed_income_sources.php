<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guaranteed_income_sources extends Model
{
    use HasFactory;
	protected $table = 'guaranteed_income_sources';
	protected $fillable = [
        'sl_no',
        'user_id',
		'client_name',
		'income_amount',
		'type',
		'frequency',
		'cola',
		'start_age',
		'end_age',
		'status', 
    ];
}
