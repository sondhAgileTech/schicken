<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiRequest extends FormRequest
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

    public function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'msg' => !empty($validator->errors()->all()) ? $validator->errors()->all()[0] : 'Invalid Parameter .',
//          'msg' => $this->getErrorStr($validator->errors()->all()),
            'data' => null,
            'error' => $errors,
            'debug' => 'Invalid Params.',
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function getErrorStr($errors = [])
    {
        $msg = '';
        if (!empty($errors)) {
            foreach ($errors as $error) {
                if (!empty($msg)) {
                    $msg = $msg . '<br/>' . $error;
                } else {
                    $msg = $error;
                }
            }
        }
        return $msg;
    }
}