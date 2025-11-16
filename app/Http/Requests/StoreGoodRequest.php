<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'vendor';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'recived_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Good name is required',
            'description.required' => 'Description is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be negative',
            'quantity.required' => 'Quantity is required',
            'quantity.numeric' => 'Quantity must be a number',
            'quantity.min' => 'Quantity cannot be negative',
            'recived_date.required' => 'Received date is required',
            'recived_date.date' => 'Received date must be a valid date',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
        ];
    }
}
