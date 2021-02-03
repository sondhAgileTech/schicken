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

    const SET_COM = 2;
    const TOPPING_COM = 5;
    const NUOC_UONG = 6;
    const DO_NHAU = 7;

    /**
     * get data from setquatity table
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'MenuProductId', 'Id');
    }

    public static function getListProductOfSetPrice() {
        return self::with(['products' => function($q) {
            return $q->where('Product.Publish', 1)
                    ->where('Product.IsDelete', 0)
                    ->where('Product.IsOption', 1)
                    ->where('Product.IsDisplayKitchen', 1)
                    ->join('SetQuantityProduct', 'SetQuantityProduct.ProductId', '=', 'Product.Id')
                    ->selectRaw('Product.Id, Product.Image, Product.DiscountPrice, Product.MenuProductId, Product.NameProduct, SetQuantityProduct.ProductId, SetQuantityProduct.RestaurantId, SUM(SetQuantityProduct.Quantity) as SumQuantity')
                    ->groupBy(['Product.Id', 'Product.Image', 'Product.DiscountPrice' ,'Product.MenuProductId', 'Product.NameProduct',
                            'SetQuantityProduct.ProductId', 'SetQuantityProduct.RestaurantId']);
        }])
        ->where('Publish', 1)
        ->where('id', self::SET_COM);
    }

    public static function getListProductOfToppingPrice() {
        return self::with(['products' => function($q) {
            return $q->where('Product.Publish', 1)
                    ->where('Product.IsDelete', 0)
                    ->where('Product.IsOption', 1)
                    ->where('Product.IsDisplayKitchen', 0)
                    ->leftJoin('SetQuantityProduct', 'SetQuantityProduct.ProductId', '=', 'Product.Id')
                    ->selectRaw('Product.Id, Product.Price, Product.Image, Product.DiscountPrice, Product.MenuProductId, Product.NameProduct, SetQuantityProduct.ProductId, SetQuantityProduct.RestaurantId, SUM(SetQuantityProduct.Quantity) as SumQuantity')
                    ->groupBy(['Product.Id', 'Product.Price','Product.Image', 'Product.DiscountPrice' ,'Product.MenuProductId', 'Product.NameProduct',
                            'SetQuantityProduct.ProductId', 'SetQuantityProduct.RestaurantId']);
        }])
        ->where('Publish', 1)
        ->where('id', self::TOPPING_COM);
    }

    public static function getListProductOfDrink() {
        return self::with(['products' => function($q) {
            return $q->where('Product.Publish', 1)
                    ->where('Product.IsDelete', 0)
                    ->where('Product.IsOption', 1)
                    ->where('Product.IsDisplayKitchen', 0)
                    ->leftJoin('SetQuantityProduct', 'SetQuantityProduct.ProductId', '=', 'Product.Id')
                    ->selectRaw('Product.Id, Product.Price, Product.Image, Product.DiscountPrice, Product.MenuProductId, Product.NameProduct, SetQuantityProduct.ProductId, SetQuantityProduct.RestaurantId, SUM(SetQuantityProduct.Quantity) as SumQuantity')
                    ->groupBy(['Product.Id', 'Product.Price','Product.Image', 'Product.DiscountPrice' ,'Product.MenuProductId', 'Product.NameProduct',
                            'SetQuantityProduct.ProductId', 'SetQuantityProduct.RestaurantId']);
        }])
        ->where('Publish', 1)
        ->where('id', self::NUOC_UONG);
    }

    public static function getListProductOfFoodDrinks() {
        return self::with(['products' => function($q) {
            return $q->where('Product.Publish', 1)
                    ->where('Product.IsDelete', 0)
                    ->whereIn('Product.IsOption', [0,1])
                    ->where('Product.IsDisplayKitchen', 0)
                    ->leftJoin('SetQuantityProduct', 'SetQuantityProduct.ProductId', '=', 'Product.Id')
                    ->selectRaw('Product.Id, Product.Price, Product.Image, Product.DiscountPrice, Product.MenuProductId, Product.NameProduct, SetQuantityProduct.ProductId, SetQuantityProduct.RestaurantId, SUM(SetQuantityProduct.Quantity) as SumQuantity')
                    ->groupBy(['Product.Id', 'Product.Price','Product.Image', 'Product.DiscountPrice' ,'Product.MenuProductId', 'Product.NameProduct',
                            'SetQuantityProduct.ProductId', 'SetQuantityProduct.RestaurantId']);
        }])
        ->where('Publish', 1)
        ->where('id', self::DO_NHAU);
    }

    /**
     * get list product of categories
     */
    public static function getListProductOfCate($condition) {
        $now = Carbon::now();
        $dateFrom = strtotime(''.$now->year.'-'.$now->month.'-'.$now->day.' 00:00:00');
        $dateTo = strtotime(''.$now->year.'-'.$now->month.'-'.$now->day.' 23:59:00');
        return self::with(['products' => function($q) use ($condition, $dateFrom, $dateTo) {
            return $q->where('Product.Publish', 1)
                    ->where('Product.IsDelete', 0)
                    ->whereIn('Product.IsOption', [0,1])
                    ->whereIn('Product.IsDisplayKitchen', [0,1])
                    ->leftJoin('SetQuantityProduct', function($leftJoin)use($condition, $dateFrom, $dateTo) {
                        $leftJoin->on('SetQuantityProduct.ProductId', '=', 'Product.Id');
                        $leftJoin->whereBetween('SetQuantityProduct.CreatedTime', [$dateFrom, $dateTo]);
                        $leftJoin->where('SetQuantityProduct.RestaurantId', $condition);
                    })
                    ->selectRaw('Product.Id, Product.Price, Product.Image, Product.DiscountPrice, Product.MenuProductId, Product.NameProduct,SetQuantityProduct.CreatedTime, SetQuantityProduct.ProductId, SetQuantityProduct.RestaurantId, SUM(SetQuantityProduct.Quantity) as SumQuantity')
                    ->groupBy(['Product.Id', 'SetQuantityProduct.CreatedTime' ,'Product.Price','Product.Image', 'Product.DiscountPrice' ,'Product.MenuProductId', 'Product.NameProduct',
                            'SetQuantityProduct.ProductId', 'SetQuantityProduct.RestaurantId']);
        }])
        ->where('Publish', 1);
    }
}