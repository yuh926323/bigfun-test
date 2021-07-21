<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HouseSearchAround extends FormRequest
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
        return [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $status = 1;

        $result = [
            'status' => $status,
            'message' => 'vaildate parameters failed.',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($result, 200, [], JSON_UNESCAPED_UNICODE));
    }
}
