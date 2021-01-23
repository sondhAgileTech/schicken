<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Option extends Model
{
    use HasFactory;

    protected $table = 'Option';

    protected $primaryKey = 'Id';

    public function products()
    {
        return $this->belongsTo('App\Models\MenuOptional', 'Id');
    }
}