<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\OtpPhone;
use App\Models\User;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;
use Laravel\Socialite\Facades\Socialite;

class CustomerController extends Controller
{

    protected $jwt;

    public function __construct(JWTAuth $jwtAuth) {
        $this->jwt = $jwtAuth;
    }

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
                return $this->checkCustomerLoginByPhone($request['phone']);
            } elseif($request['typelogin'] === 2) {
                $provider='google';
                $driver= Socialite::driver($provider);
                $socialUserObject = $driver->userFromToken($request['tokensocial']);
                return $this->checkCustomerLoginBySocial($socialUserObject->id, $socialUserObject->name);
            }
        }
    }
    
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if(isset($request)) {
            if((int)$request['typelogin'] === 1) {
                $phone = Customer::CheckLoginPhoneCustomer($request['phone'])->first();
                if (!($token = $this->jwt->fromUser($phone))) {
                    return response()->json([
                        'status' => 'error',
                        'error' => 'invalid.credentials',
                        'msg' => 'Invalid Credentials.'
                    ], Response::HTTP_BAD_REQUEST);
                }
                return $this->respondWithToken($token);
            }
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 2629743
        ], Response::HTTP_OK);
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
            return response()->json(array('success' => true, 'phone'=> $checkPhone->Phone , 'status_next_page' => $checkPhone->TypeLogin), Response::HTTP_OK);
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
                return response()->json(array('success' => true, 'phone'=> $newCustomer->phone ,'status_next_page' => $newCustomer->typelogin), Response::HTTP_OK);
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
            return response()->json(array('success' => true, 'status_next_page' => $checkSocial->TypeLogin, 'info'=>$checkSocial ), Response::HTTP_OK);
        } else {
            $newCustomer->phone = "";
            $newCustomer->fullname = $name;
            $newCustomer->tokensocial = $tokensocial;
            $newCustomer->typelogin = 2;
            if($newCustomer->save()) {
                return response()->json(array('success' => true, 'status_next_page' => $newCustomer->typelogin, 'info'=>$newCustomer ), Response::HTTP_OK);
            }
        }
    }
}
