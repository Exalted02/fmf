<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_portfolio_Desires extends Model
{
    use HasFactory;
	protected $table = 'client_portfolio_desires';
	protected $fillable = [
        'user_id',
		'client_name',
		'client_age',
		'partner_name',
		'partner_age',
		'current_portfolio_value',
		'desired_gross_income_retirement',
		'desired_retirement_age',
		'COLA',
		'cola_age',
		'assumed_return',
		'RIPG',
		'status', 
    ];
	public function get_representative_details()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
