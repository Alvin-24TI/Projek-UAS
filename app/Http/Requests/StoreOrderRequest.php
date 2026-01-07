<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000',
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
            'user_id.required' => 'Pelanggan harus dipilih',
            'user_id.exists' => 'Pelanggan yang dipilih tidak ada',
            'items.required' => 'Minimal satu item produk harus dipilih',
            'items.min' => 'Minimal satu item produk harus ada',
            'items.*.product_id.required' => 'Produk harus dipilih',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak ada',
            'items.*.quantity.required' => 'Jumlah harus diisi',
            'items.*.quantity.integer' => 'Jumlah harus berupa angka',
            'items.*.quantity.min' => 'Jumlah minimal 1',
            'items.*.quantity.max' => 'Jumlah maksimal 1000',
        ];
    }
}
