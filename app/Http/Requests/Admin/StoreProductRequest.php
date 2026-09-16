<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'required|unique:products',
            'game_id' => 'required|exists:games,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'delivery_type' => 'required|in:instant,manual,code,account_data,instruction',
            'status' => 'required|in:active,inactive',
            'main_image' => 'nullable|image|max:2048',
        ];
    }
}
