<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roth_conversion_year extends Model
{
    use HasFactory;
	protected $table = 'roth_conversion_years';
	protected $fillable = [
        'sl_no',
        'user_id',
        'year',
        'wife_roth_year',
        'rmd_age',
        'show_specific_year',
    ];
}
