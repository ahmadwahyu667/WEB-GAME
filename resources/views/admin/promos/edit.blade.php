@extends('layouts.admin')

@section('title', 'Edit Promo')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Edit Promo</h1>
    <a href="{{ route('admin.promos.index') }}" class="btn-admin btn-secondary">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
    </a>
</div>

<div class="admin-card p-6">
    <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group mb-4">
                <label class="form-label" for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $promo->title) }}" required>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="code">Code</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $promo->code) }}" required>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="type">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="percentage" {{ old('type', $promo->type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="fixed" {{ old('type', $promo->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                </select>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="value">Value</label>
                <input type="number" name="value" id="value" class="form-control" value="{{ old('value', $promo->value) }}" required>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="min_purchase">Min Purchase</label>
                <input type="number" name="min_purchase" id="min_purchase" class="form-control" value="{{ old('min_purchase', $promo->min_purchase) }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="max_discount">Max Discount</label>
                <input type="number" name="max_discount" id="max_discount" class="form-control" value="{{ old('max_discount', $promo->max_discount) }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="starts_at">Starts At</label>
                <input type="datetime-local" name="starts_at" id="starts_at" class="form-control" value="{{ old('starts_at', $promo->starts_at ? \Carbon\Carbon::parse($promo->starts_at)->format('Y-m-d\TH:i') : '') }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="ends_at">Ends At</label>
                <input type="datetime-local" name="ends_at" id="ends_at" class="form-control" value="{{ old('ends_at', $promo->ends_at ? \Carbon\Carbon::parse($promo->ends_at)->format('Y-m-d\TH:i') : '') }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="usage_limit">Usage Limit</label>
                <input type="number" name="usage_limit" id="usage_limit" class="form-control" value="{{ old('usage_limit', $promo->usage_limit) }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" {{ old('status', $promo->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $promo->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="btn-admin btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
