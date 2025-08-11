<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing_plan extends Model
{
    use HasFactory;
	protected $fillable = [
        'plan_type', 'plan_name', 'status', 
    ];
}
