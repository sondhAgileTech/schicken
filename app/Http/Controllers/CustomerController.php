<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\OtpPhone;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        if(isset($request)) {
            if($request['typelogin'] === 1) {
                $this->checkCustomerLoginByPhone($request['phone']);
            } elseif($request['typelogin'] === 2) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = ['token' => $token];
                    return response($response, 200);
                } else {
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }
            // $this->checkCustomerLoginBySocial($request['tokensocial'], $request['fullname']);
            }
        }
    }
 
    /**
     * Display the specified resource.
     *
     * @param  array  $request
     */
    public function checkCustomerLoginByPhone($phone)
    {
        $optPhone = new OtpPhone();
        $newCustomer = new Customer();
        $checkPhone = Customer::CheckLoginPhoneCustomer($phone)->first();
        if($checkPhone != null) {
            // return 1;
            // if(OtpPhone::CheckOtpPhoneCode($checkPhone->otpphoneid)) {
            //     $checkPhone->otpphoneid = "1234567";
            //     $checkPhone->save();
            // }
        } else {
            $optPhone->otpcode = "123456";
            $optPhone->save();

            $newCustomer->phone = $phone;
            $newCustomer->otpphoneid = $optPhone->id;
            $newCustomer->typelogin = 1;
            if($newCustomer->save()) {
                return response()->json(array('success' => true, 'status_next_page' => $newCustomer->typelogin), Response::HTTP_OK);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  array  $request
     */
    public function checkCustomerLoginBySocial($tokensocial, $name)
    {
        $newCustomer = new Customer();
        $checkSocial = Customer::CheckLoginSocialCustomer($tokensocial)->first();
        if($checkSocial != null) {
            // if(OtpPhone::CheckOtpPhoneCode($checkPhone->otpphoneid)) {
            //     $checkPhone->otpphoneid = "1234567";
            //     $checkPhone->save();
            // }ss
        } else {
            $newCustomer->phone = "";
            $newCustomer->fullname = $name;
            $newCustomer->tokensocial = $tokensocial;
            $newCustomer->typelogin = 2;
            if($newCustomer->save()) {

                return response()->json(array('success' => true, 'status_next_page' => $newCustomer->typelogin), Response::HTTP_OK);
            }
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
         
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShopMember $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
