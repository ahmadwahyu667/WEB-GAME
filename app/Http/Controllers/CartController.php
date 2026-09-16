<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function index(): View
    {
        $cart = $this->cartService->getCart();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $this->cartService->add($request->product_id, $request->quantity);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang',
                ]);
            }

            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan ke keranjang: '.$e->getMessage(),
                ], 400);
            }

            return back()->with('error', 'Gagal menambahkan ke keranjang: '.$e->getMessage());
        }
    }

    public function update(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $this->cartService->updateQuantity($id, $request->quantity);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kuantitas berhasil diperbarui',
                ]);
            }

            return back()->with('success', 'Kuantitas berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui kuantitas: '.$e->getMessage(),
                ], 400);
            }

            return back()->with('error', 'Gagal memperbarui kuantitas: '.$e->getMessage());
        }
    }

    public function remove(Request $request, string $id): JsonResponse|RedirectResponse
    {
        try {
            $this->cartService->remove($id);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk dihapus dari keranjang',
                ]);
            }

            return back()->with('success', 'Produk dihapus dari keranjang');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus produk: '.$e->getMessage(),
                ], 400);
            }

            return back()->with('error', 'Gagal menghapus produk: '.$e->getMessage());
        }
    }

    public function clear(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $this->cartService->clear();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil dikosongkan',
                ]);
            }

            return back()->with('success', 'Keranjang berhasil dikosongkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengosongkan keranjang: '.$e->getMessage(),
                ], 400);
            }

            return back()->with('error', 'Gagal mengosongkan keranjang: '.$e->getMessage());
        }
    }
}
