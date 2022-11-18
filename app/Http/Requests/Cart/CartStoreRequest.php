<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartStoreRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'qty' => 'required|integer'
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'qty.required' => 'The quantity field is required.',
            'qty.integer' => 'The quantity must be an integer.'
        ];
    }
}
