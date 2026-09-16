<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['game', 'category'])->where('is_active', true);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('game')) {
            $query->whereHas('game', function (Builder $q) use ($request) {
                $q->where('slug', $request->game);
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function (Builder $q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->filled('promo')) {
            $query->where('is_promo', true);
        }

        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'terlaris' => $query->orderByDesc('sold_count'),
            'harga_terendah' => $query->orderBy('price'),
            'harga_tertinggi' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('rating'),
            default => $query->latest(),
        };

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data produk',
            'data' => $products,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $product = Product::with(['game', 'category', 'images', 'reviews' => function ($q) {
                $q->where('is_approved', true);
            }])->where('slug', $slug)->where('is_active', true)->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil detail produk',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'data' => null,
            ], 404);
        }
    }
}
