<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends ApiRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this['typelogin'] === 1) {
            return [
                'phone' => 'string|required',
            ];
        } else {
            return [
                'tokensocial' => 'string',
                'fullname' => 'string',
            ];
        }

        // regex:/^[0-9]{0,15}$/
    }

    /**
     * Get custom attributes for validation errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'phone' => 'Số điện thoại là bắt buộc',
        ];
    }
}