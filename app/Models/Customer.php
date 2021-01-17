<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Customer extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'customer';

    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'typelogin',
        'tokensocial',
        'otpphoneid'
    ];

    /**
     * Scope a query to only include show news to show
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCheckLoginPhoneCustomer($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    /**
     * Scope a query to only include show news to show
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCheckLoginSocialCustomer($query, $tokensocial)
    {
        return $query->where('tokensocial', $tokensocial);
    }
}
