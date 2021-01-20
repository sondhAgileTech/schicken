<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Http\Response;

class RestaurantController extends Controller
{
    public function getRestaurant() {
        $data = Restaurant::getListRestaurant(21.001291,105.848442)->get();
        return response()->json(array('success' => true, 'data'=> $data), Response::HTTP_OK);
    }
}