<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get or create a cart for the authenticated user.
     */
    public function getOrCreateCart(): Cart
    {
        $user = Auth::user();

        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * Add a product to the cart.
     */
    public function addItem(int $productId, int $quantity = 1): CartItem
    {
        $cart = $this->getOrCreateCart();
        $product = Product::active()->inStock()->findOrFail($productId);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            $this->validateQuantity($product, $newQuantity);
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $this->validateQuantity($product, $quantity);
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem->load('product');
    }

    /**
     * Update item quantity in cart.
     */
    public function updateQuantity(int $cartItemId, int $quantity): CartItem
    {
        $cart = $this->getOrCreateCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->findOrFail($cartItemId);

        $product = Product::findOrFail($cartItem->product_id);
        $this->validateQuantity($product, $quantity);

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->load('product');
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $cartItemId): bool
    {
        $cart = $this->getOrCreateCart();

        return CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->delete() > 0;
    }

    /**
     * Clear the entire cart.
     */
    public function clearCart(): bool
    {
        $cart = $this->getOrCreateCart();

        return $cart->items()->delete() > 0;
    }

    /**
     * Alias for clearCart() — used by CartController.
     */
    public function clear(): bool
    {
        return $this->clearCart();
    }

    /**
     * Alias for addItem() — used by CartController.
     */
    public function add(int $productId, int $quantity = 1): CartItem
    {
        return $this->addItem($productId, $quantity);
    }

    /**
     * Alias for removeItem() — used by CartController.
     */
    public function remove(int $cartItemId): bool
    {
        return $this->removeItem($cartItemId);
    }

    /**
     * Alias for getCartWithItems() — used by CartController and CheckoutController.
     */
    public function getCart(): Cart
    {
        return $this->getCartWithItems();
    }

    /**
     * Get cart with items and products.
     */
    public function getCartWithItems(): Cart
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.images', 'items.product.game']);

        return $cart;
    }

    /**
     * Calculate cart summary.
     *
     * @return array{subtotal: float, discount: float, total: float, item_count: int}
     */
    public function getCartSummary(): array
    {
        $cart = $this->getCartWithItems();
        $subtotal = 0;
        $discount = 0;
        $itemCount = 0;

        foreach ($cart->items as $item) {
            $product = $item->product;
            $originalPrice = (float) $product->price;
            $effectivePrice = $product->discount_price
                ? (float) $product->discount_price
                : $originalPrice;

            $subtotal += $originalPrice * $item->quantity;
            $discount += ($originalPrice - $effectivePrice) * $item->quantity;
            $itemCount += $item->quantity;
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal - $discount,
            'item_count' => $itemCount,
        ];
    }

    /**
     * Validate that the requested quantity doesn't exceed stock.
     */
    private function validateQuantity(Product $product, int $quantity): void
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Jumlah minimal adalah 1.');
        }

        if ($quantity > $product->stock) {
            throw new \InvalidArgumentException(
                "Stok tidak mencukupi. Stok tersedia: {$product->stock}."
            );
        }
    }
}
