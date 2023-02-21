<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class storeOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $ecommerce_id = $this->order_id;

        return [
            'order_id'          => 'required|integer',
            'customer_name'     => 'required|string|max:100',
            'shipping_address'  => 'required|string|max:2048',
            'delivery_date'     => 'required|date',
            'partner_id' => [
                'required',
                Rule::unique('orders')->where(function ($query) use($ecommerce_id) {
                    return $query->where('ecommerce_id', $ecommerce_id);
                })
            ],
            'items.*.ecommerce_item_id' => 'required|numeric',
            'items.*.partner_item_id'   => 'required|numeric',
            'items.*.item_quantity'     => 'required|numeric',

        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
