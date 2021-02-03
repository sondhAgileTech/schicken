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

    public $cats = [
        'MenuProductId' => 'int'
    ];

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

    /**
     * add data to bill group
     */
    public static function addBillGroup($condition)
    {
        $now = Carbon::now();
        return DB::table('BillGroup')->insertGetId([
            'RestaurentId' => $condition['restaurantId'],
            'RestaurantFloorId' => $condition['restaurantFloorId'],
            'DinnerTableId' => $condition['dinnerTableId'],
            'NumberTableId' => 0,
            'StaffOrderId' => 0,
            'TimeCreated' => strtotime($now),
            'Isdelete' => 0,
            'ModifiedTime' => 0,
            'ShiftId' => 0,
            'SourceBillId' => $condition['sourceBillId'],
            'TimeOrder' => strtotime($now),
        ]);
    }
    
    /**
     * add data to bill group
     */
    public static function addBillService($condition)
    {
        $now = Carbon::now();
        return DB::table('BillService')->insertGetId([
            'BillGroupId' => $condition['idBillGroup'],
            'RestaurantId' => $condition['restaurantId'],
            'RestaurantFloorId' => $condition['restaurantFloorId'],
            'DinnerTableId' => $condition['dinnerTableId'],
            'NumberTable' => 0,
            'StaffOrderId' => 0,
            'StaffKitchenId' => 1,
            'TimeCreate' => strtotime($now),
            'KitchenTimeStart' => 0,
            'KitchenTimeDone' => 0,
            'StatusValueId' => 6,
            'TotalCollectMoney' => $condition['totalMoney'],
            'IsDelete' => 0,
            'OrderOfTheDay' => 0,
            'Note' => $condition['note'],
            'ShiftId' => 0,
            'ModifiedTime' => 0,
            'SourceBillId' => $condition['sourceBillId'],
            'TimeOrder' => strtotime($now),
            'PercentDiscount' => 0,
            'PercentDiscountSourceBill' => 0,
            'InfoSourceBill' => "",
            'TimeOfDelivering' => 0,
            'CustomerId' => $condition['customerId'],
            'TotalMoney' => $condition['totalMoney'],
            'PromotionType' => NULL,
        ]);
    }

    /**
     * add data to bill group
     */
    public static function addBillServiceDetail($condition)
    {
        $now = Carbon::now();
        return DB::table('BillServiceDetail')->insertGetId([
            'BillServiceId' => $condition['billServiceId'],
            'ProductId' => $condition['productId'],
            'DiscountPercentages' => 0,
            'DiscountPrice' => $condition['billServiceId'],
            'Price' => $condition['price'],
            'Coste' => 0,
            'CollectMoney' => $condition['totalMoney'],
            'IsDelete' => 0,
            'CreateTime' => strtotime($now),
            'ModifiedTime' => 0,
            'Note' => $condition['note'],
            'Quantity' => 1,
            'PriceSourceBillId' => $condition['price'],
            'OptionOfProductId' => $condition['typeOptionId'] != NULL ? $condition['productId'] : NULL ,
            'ProductName' => NULL
        ]);
    }

    public static function addOptionBillServiceDetail($condition) {
        $now = Carbon::now();
        return DB::table('OptionBillServiceDetail')->insertGetId([
            'BillServiceDetailId' => $condition['billServiceDetailId'],
            'OptionId' => $condition['optionId'],
            'Price' => $condition['priceOption'],
            'CreateTime' => strtotime($now),
            'ModifiedTime' => 0,
            'IsDelete' => 0,
        ]);
    }
}