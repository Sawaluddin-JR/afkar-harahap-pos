<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:255', 'unique:products,product_code'],
            // 'product_barcode_symbology' => ['required', 'string', 'max:255'],
            // 'product_unit' => ['required', 'string', 'max:255'],
            'product_quantity' => ['required', 'integer', 'min:1'],
            'product_cost' => ['required', 'numeric', 'max:2147483647'],
            'product_price' => ['required', 'numeric', 'max:2147483647'],
            '' => ['nullable', 'integer', 'min:0'],
            // 'product_order_tax' => ['nullable', 'integer', 'min:0', 'max:100'],
            // 'product_tax_type' => ['nullable', 'integer'],
            'product_note' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['required', 'integer']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->product_quantity < $this->product_stock_alert) {
                $validator->errors()->add('product_quantity', 'Stok produk tidak boleh kurang dari stok alert(10)');
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create_products');
    }
}
