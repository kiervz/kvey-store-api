<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateQtyRequest extends FormRequest
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
            'action' => 'required|string|in:add,subtract',
            'cart_id' => 'required|integer',
            'total' => 'sometimes|integer|max:99'
        ];
    }

    public function messages(): array
    {
        return [
            'action.in' => "The :attribute field must be 'add' or 'subtract'."
        ];
    }
}
