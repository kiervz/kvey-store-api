<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartSelectRequest extends FormRequest
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
            'cart_id' => 'required|integer',
            'is_selected' => 'required|integer|in:0,1'
        ];
    }

    public function messages(): array
    {
        return [
            'is_selected.required' => 'The is_selected field is required.',
            'is_selected.integer' => 'The is_selected must be an integer.',
            'is_selected.in' => 'The is_selected field must be 0 or 1.'
        ];
    }
}
