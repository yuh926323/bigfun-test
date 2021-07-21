<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HouseSearch extends FormRequest
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
            'distict' => 'string|nullable',
            'min_house_holds' => 'integer|min:0|nullable',
            'max_house_holds' => 'integer|min:0|required_with:min_house_holds|greater_than_field:min_house_holds',
        ];
    }

    public function attributes()
    {
        return [
            'max_house_holds' => 'max_house_holds',
        ];
    }

    public function messages()
    {
        return [
            'max_house_holds.greater_than_field' => ':attribute need to greater than :field.',
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
