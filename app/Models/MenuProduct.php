<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class MenuProduct extends Model
{
    use HasFactory;

    protected $table = 'MenuProduct';

    protected $primaryKey = 'Id';

    /**
     * get data from setquatity table
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'MenuProductId');
    }

    /**
     * get list product of categories
     */
    public static function getListProductOfCate($condition) {
        return self::with(['products' => function($subQuery) use ($condition) {
                    // return $subQuery;
                    return $subQuery->where('Product.Publish', 1);
                        // ->leftJoin('SetQuantityProduct', function($join) use ($condition){
                        //     // $join->select('SetQuantityProduct.RestaurantId', 'SetQuantityProduct.ProductId');
                        //     $join->on('SetQuantityProduct.ProductId', '=', 'Product.Id');
                        //     $join->where('SetQuantityProduct.RestaurantId', 1);
                        //     // $join->groupBy('SetQuantityProduct.RestaurantId', 'SetQuantityProduct.ProductId');
                        // })

                        // ->join('SetQuantityProduct', 'SetQuantityProduct.ProductId', '=', 'Product.Id')
                        // ->select('Product.*', 'SetQuantityProduct.*');
                                    
                    }])
                    ->where('Publish', 1);
        
        
        
        // with(['products' => function($subQuery) {
        //     return  $subQuery->selectRaw('Product.Id AS idPro,Product.Price,Product.DiscountPrice')
        //                      ->leftJoin('SetQuantityProduct as sqp', 'sqp.ProductId', '=', 'Product.idPro')
        // }])->get();
    }
}