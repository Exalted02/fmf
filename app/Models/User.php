<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'company_name',
        'address',
        'country',
        'city',
        'state',
        'zipcode',
        'phone_number',
        'dob',
        'gender_id',
        'upload_tax_lisence',
        'otp',
        'profile_image',
        'latitude',
        'longitude',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
	
	public function get_country()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }
	public function get_state()
    {
        return $this->hasOne(State::class, 'id', 'state');
    }
	public function get_client_portfolio()
    {
        return $this->hasMany(Client_portfolio_Desires::class, 'user_id', 'id');
    }
	public function get_subscription()
    {
        return $this->hasMany(Subscriptions::class, 'user_id', 'id');
    }
}
