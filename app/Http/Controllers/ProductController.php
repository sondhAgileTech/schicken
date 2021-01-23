<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuProduct;
use App\Models\Product;
use App\Models\MenuOptional;
use App\Models\ProductConfigMenuOptions;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Get list Product of restaurant
     */
    public function getProduct() {
        $data = MenuProduct::getListProductOfCate(1)->get();
        return response()->json(array('success' => true, 'data'=> $data), Response::HTTP_OK);
    }

    /**
     * Get detail of product
     */
    public function getDetailProduct(Request $request) {
        $productId = $request['product_id'];
        if(Product::GetProductId($productId)->first()) {
            $option = [];
            $data = Product::getDetailProduct($productId)->get();
            foreach ($data[0]['ProductConfigMenuOptions'] as $value) {
                $option[] = MenuOptional::getOptionOfProduct($value['MenuOptionId'])->first();
            }
            return response()->json(array('success' => true, 'product' => $data , 'option' => $option), Response::HTTP_OK);
        } else {
            return response()->json(array('success' => false), Response::HTTP_OK);
        }
    }
}