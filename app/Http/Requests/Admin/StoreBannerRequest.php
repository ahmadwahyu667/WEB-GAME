<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'image' => 'required|image|max:2048',
            'url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ];
    }
}
