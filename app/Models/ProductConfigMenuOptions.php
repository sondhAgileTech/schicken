<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ProductConfigMenuOptions extends Model
{
    use HasFactory;

    protected $table = 'ProductConfigMenuOptions';

    protected $primaryKey = 'Id';

    public function products()
    {
        return $this->belongsTo('App\Models\Product', 'Id');
    }


    public static function getDetailProduct2($condition)
    {
        return self::select(['ProductConfigMenuOptions.*'])
                    ->where('Product.Id', $condition);
    }
}