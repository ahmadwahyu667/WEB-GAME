<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'required|unique:categories,slug,'.$this->route('category')->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|image|max:2048',
        ];
    }
}
