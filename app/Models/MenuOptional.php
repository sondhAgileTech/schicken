<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class MenuOptional extends Model
{
    use HasFactory;

    protected $table = 'MenuOptional';

    protected $primaryKey = 'Id';

    /**
     * get data from setquatity table
     */
    public function options()
    {
        return $this->hasMany('App\Models\Option', 'MenuOptionId');
    }

    public static function getOptionOfProduct($optionId) {
        return self::with(['options' => function($subQuery) use($optionId){
            $subQuery->where('Option.MenuOptionId', $optionId);
            $subQuery->where('Option.Publish', 1);
            $subQuery->where('Option.IsDelete', 0);
        }])->where('MenuOptional.Id', $optionId);            
    } 

}