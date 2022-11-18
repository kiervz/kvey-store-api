<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateStatusRequest extends FormRequest
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
            'cart_id' => 'required|array',
            'status' => 'required|in:P,C,D'
        ];
    }

    public function messages()
    {
        return [
            'status.in' => "The :attribute field must be 'P', 'C' or 'D'."
        ];
    }
}
