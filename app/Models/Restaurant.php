<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurant';

    protected $primaryKey = 'Id';

    /**
     * get data from setquatity table
     */
    public function quantity()
    {
        return $this->hasMany('App\Models\SetQuantityProduct', 'RestaurantId');
    }

    /**
     * Get list Restaurant
     * @param array $condition
     */
    public static function getListRestaurant($latitude , $longitude)
    {
        $now = Carbon::now();
        $dateFrom = strtotime(''.$now->year.'-'.$now->month.'-'.$now->day.' 00:00:00');
        $dateTo = strtotime(''.$now->year.'-'.$now->month.'-'.$now->day.' 23:59:00');
        return Restaurant::selectRaw("*,( 6371 * acos( cos( radians(?) ) * cos( radians( Latitude ) ) * cos( radians( Longtitude ) - radians(?)) + sin( radians(?) ) * sin( radians( Latitude ) ))) AS distance", [$latitude, $longitude, $latitude])
                            ->with(['quantity' => function($subQuery) use ($dateFrom, $dateTo ) {
                            return $subQuery->selectRaw('SetQuantityProduct.RestaurantId,sum(SetQuantityProduct.Quantity) as totalQuantity ,sum(SetQuantityProduct.QuantitySold) as totalQuantitySold')
                                            ->whereBetween('CreatedTime', [$dateFrom, $dateTo])
                                            ->groupBy('SetQuantityProduct.RestaurantId');
                            }])
                            ->where('Publish',1)
                            ->where('IsDelete',0);
    }
}