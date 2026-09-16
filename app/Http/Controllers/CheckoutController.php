<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService
    ) {}

    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }

        return view('checkout.index', compact('cart'));
    }

    public function process(Request $request): RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_whatsapp' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'string'],
        ]);

        try {
            if ($validated['customer_whatsapp'] && ! auth()->user()->phone) {
                auth()->user()->update(['phone' => $validated['customer_whatsapp']]);
            }

            $items = $cart->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ])->toArray();

            $result = $this->orderService->createOrder(auth()->id(), $items);
            $order = $result['order'];
            $this->cartService->clear();

            return redirect()->route('payment.show', $order);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pesanan: '.$e->getMessage())->withInput();
        }
    }

    public function direct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_whatsapp' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'string'],
        ]);

        try {
            if ($validated['customer_whatsapp'] && ! auth()->user()->phone) {
                auth()->user()->update(['phone' => $validated['customer_whatsapp']]);
            }

            $items = [
                [
                    'product_id' => (int) $validated['product_id'],
                    'quantity' => (int) $validated['quantity'],
                ],
            ];

            $result = $this->orderService->createOrder(auth()->id(), $items);
            $order = $result['order'];

            return redirect()->route('payment.show', $order);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pesanan: '.$e->getMessage())->withInput();
        }
    }
}
