<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetQuantityProduct extends Model
{
    use HasFactory;

    protected $table = 'SetQuantityProduct';

    protected $primaryKey = 'Id';

    public function restaurant()
    {
      return $this->belongsTo('App\Models\Restaurant', 'Id');
    }

}