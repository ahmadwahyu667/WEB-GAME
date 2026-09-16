<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Game;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $popularProducts = Product::with(['game', 'images'])->active()->orderByDesc('sold_count')->take(12)->get();
        $games = Game::where('is_active', true)->withCount('products')->get();
        $recentOrders = Order::with(['items.product'])->where('status', 'completed')->latest()->take(10)->get();
        $news = News::where('status', 'published')->latest()->take(4)->get();
        $promos = Product::with(['game', 'images'])->active()->whereHas('promos')->take(6)->get();

        return view('home.index', compact('banners', 'popularProducts', 'games', 'recentOrders', 'news', 'promos'));
    }

    public function games(): View
    {
        $games = Game::where('is_active', true)->withCount('products')->paginate(12);

        return view('home.games', compact('games'));
    }

    public function gameProducts(string $slug): View
    {
        $game = Game::where('slug', $slug)->firstOrFail();
        $products = Product::with(['game', 'images'])->where('game_id', $game->id)->active()->paginate(12);

        return view('home.game_products', compact('game', 'products'));
    }

    public function categoryProducts(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::with(['game', 'images'])->where('category_id', $category->id)->active()->paginate(12);

        return view('home.category_products', compact('category', 'products'));
    }

    public function promo(): View
    {
        $promos = Product::with(['game', 'images'])->active()->whereHas('promos')->paginate(12);

        return view('home.promo', compact('promos'));
    }

    public function faq(): View
    {
        return view('home.faq');
    }

    public function about(): View
    {
        return view('home.about');
    }

    public function contact(): View
    {
        return view('home.contact');
    }
}
