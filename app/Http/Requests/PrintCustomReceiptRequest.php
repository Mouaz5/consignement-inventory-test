<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrintCustomReceiptRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
            'goods' => 'required|array',
            'goods.*.id' => 'required|exists:goods,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vendor_id.required' => 'Vendor is required',
            'vendor_id.exists' => 'Selected vendor does not exist',
            'goods.required' => 'At least one good is required',
            'goods.array' => 'Goods must be an array',
            'goods.*.id.required' => 'Good ID is required',
            'goods.*.id.exists' => 'Selected good does not exist',
        ];
    }
}
