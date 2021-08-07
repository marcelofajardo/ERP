<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCouponRequest extends FormRequest
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
            'code' => 'required|unique:coupons',
            'start' => 'required|date_format:Y-m-d H:i',
            'expiration' => 'sometimes|nullable|date_format:Y-m-d H:i|after:start',
            'discount_fixed' => 'nullable|numeric',
            'discount_percentage' => 'sometimes|nullable|numeric',
            'minimum_order_amount' => 'sometimes|nullable|integer',
            'maximum_usage' => 'sometimes|nullable|integer',
        ];
    }
}
