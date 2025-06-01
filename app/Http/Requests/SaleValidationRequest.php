<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleValidationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'products_arr' => 'required|array|min:1',
            'products_arr.*.id' => 'required|integer',
            'products_arr.*.product_name' => 'required|string|max:255',
            'client' => 'required|array',
            'client.name' => 'required|string|max:255',
            'client.phone' => 'required|string|max:20',
            'client.address' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'order_no' => 'required|unique:sales',
            'warehouse_id' => 'required|integer'
        ];
    }
}
