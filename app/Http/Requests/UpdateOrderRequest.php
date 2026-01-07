<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,processing,completed,cancelled',
            'items' => 'sometimes|array',
            'items.*.product_id' => 'sometimes|exists:products,id',
            'items.*.quantity' => 'sometimes|integer|min:1|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak ada',
            'items.*.quantity.integer' => 'Jumlah harus berupa angka',
            'items.*.quantity.min' => 'Jumlah minimal 1',
            'items.*.quantity.max' => 'Jumlah maksimal 1000',
        ];
    }
}
