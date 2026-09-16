<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function index(): JsonResponse
    {
        try {
            $cart = $this->cartService->getCart();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data keranjang',
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keranjang: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $this->cartService->add($request->product_id, $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'data' => $this->cartService->getCart(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ke keranjang: '.$e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $this->cartService->updateQuantity($id, $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Kuantitas berhasil diperbarui',
                'data' => $this->cartService->getCart(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kuantitas: '.$e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function remove(string $id): JsonResponse
    {
        try {
            $this->cartService->remove($id);

            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang',
                'data' => $this->cartService->getCart(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: '.$e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function clear(): JsonResponse
    {
        try {
            $this->cartService->clear();

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan',
                'data' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan keranjang: '.$e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
