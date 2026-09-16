<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['game', 'category', 'images'])->active();

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
            $query->whereHas('promos');
        }

        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'terlaris' => $query->orderByDesc('sold_count'),
            'harga_terendah' => $query->orderBy('price'),
            'harga_tertinggi' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('rating'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function show(string $slug): View
    {
        $product = Product::with(['game', 'category', 'images', 'reviews' => function ($q) {
            $q->where('is_approved', true)->with('user')->latest()->take(10);
        }])->where('slug', $slug)->active()->firstOrFail();

        $relatedProducts = Product::with(['game', 'images'])->where('id', '!=', $product->id)
            ->where(function (Builder $query) use ($product) {
                $query->where('game_id', $product->game_id)
                    ->orWhere('category_id', $product->category_id);
            })
            ->active()
            ->take(4)
            ->get();

        $games = Game::where('is_active', true)->get();

        return view('products.show', compact('product', 'relatedProducts', 'games'));
    }
}
