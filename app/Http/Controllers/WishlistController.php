<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = Auth::user()->wishlists()->with('product')->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Product $product): JsonResponse
    {
        $user = Auth::user();

        if ($user->wishlists()->where('product_id', $product->id)->exists()) {
            $user->wishlists()->where('product_id', $product->id)->delete();
            $status = 'removed';
            $message = 'Produk dihapus dari wishlist';
        } else {
            $user->wishlists()->create(['product_id' => $product->id]);
            $status = 'added';
            $message = 'Produk ditambahkan ke wishlist';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function moveToCart(Product $product, CartService $cartService): RedirectResponse
    {
        try {
            $cartService->add($product->id, 1);
            Auth::user()->wishlists()->where('product_id', $product->id)->delete();

            return back()->with('success', 'Produk dipindahkan ke keranjang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memindahkan ke keranjang.');
        }
    }
}
