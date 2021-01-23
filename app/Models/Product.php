<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Product extends Model
{
    use HasFactory;

    protected $table = 'Product';

    protected $primaryKey = 'Id';

    public function ProductConfigMenuOptions()
    {
        return $this->hasMany('App\Models\ProductConfigMenuOptions', 'ProductId');
    }

    /**
     * Scope a query to only include show news to show
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetProductId($query, $condition)
    {
        return $query->where('Id', $condition);
    }

    /**
     * GetProduct by Id
     */
    public static function getDetailProduct($condition)
    {
        return self::with('ProductConfigMenuOptions')
                    ->select(['Product.*'])
                    ->where('Product.Id', $condition)
                    ->where('Product.Publish', 1);
    }



    

    // public static function getQuerySearch($condition) {
    //     return self::whereHas('product_config_menu_options', function($q){
    //        return $q->leftJoin('MenuOptional', function($j){
    //             $j->select('MenuOptional.Id as testtttt');
    //             $j->on('MenuOptional.Id' , '=' , 'ProductConfigMenuOptions.MenuOptionId');
    //         })->get();
    //     });
    // }
}