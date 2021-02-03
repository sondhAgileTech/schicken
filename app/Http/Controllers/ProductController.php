<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuProduct;
use App\Models\Product;
use App\Models\BillGroup;
use App\Models\BillServiceDetail;
use App\Models\BillService;
use App\Models\OptionBillServiceDetail;
use App\Models\MenuOptional;
use App\Models\ProductConfigMenuOptions;
use Illuminate\Http\Response;
use App\Models\SetQuantityProduct;
use App\Library\TokenGenerator;
use Carbon\Carbon;
use DB;

class ProductController extends Controller
{
    /**
     * Get list Product of restaurant
     */
    public function getProduct(Request $request) {
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

    public function addBill(Request $request) {

        if(isset($request)) {
            $input = [];
            $input['restaurantId'] = $request['restaurantId'];
            $input['restaurantFloorId'] = $request['floorId'];
            $input['dinnerTableId'] = $request['dinnerTableId'];
            $input['sourceBillId'] = $request['sourceBillId'];
            $input['customerId'] = $request['customerId'];
            $idBillGroup = Product::addBillGroup($input);
            $input['idBillGroup'] = $idBillGroup;
            $cart = $request['cart'];
            if($request['sourceBillId'] === 1) {
                foreach ($cart as $value) {
                    $input['totalMoney'] = $value['totalMoney'];
                    $input['note'] = $value['note'];
                    $input['price'] = $value['price'];
                    $input['productId'] = $value['id'];
                    $input['typeOptionId'] = $value['typeOptionId'];
                    $idBillService = Product::addBillService($input);
                    $input['billServiceId'] = $idBillService;
                    for ($i=1; $i <= $value['count']; $i++) { 
                        $idBillServiceDetail = Product::addBillServiceDetail($input);
                        if(count($value['options']) > 0) {
                            foreach ($value['options'] as $option) {
                                $input['billServiceDetailId'] = $idBillServiceDetail;
                                $input['optionId'] = $option['id'];
                                $input['priceOption'] = $option['price'];
                                Product::addOptionBillServiceDetail($input);
                            }
                        }
                    }
                }

            } elseif($request['sourceBillId'] === 7) {
                $idBillService = Product::addBillService($input);
            }
            return response()->json(array('status' => true), Response::HTTP_OK);
        }  else {
            return response()->json(array('status' => false), Response::HTTP_OK);
        }
    }
}