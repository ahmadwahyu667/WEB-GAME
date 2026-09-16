<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\AdminAuditLog;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['game', 'category']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->has('game_id') && $request->game_id != '') {
            $query->where('game_id', $request->game_id);
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15);
        $games = Game::all();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'games', 'categories'));
    }

    public function create()
    {
        $games = Game::all();
        $categories = Category::all();

        return view('admin.products.create', compact('games', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('main_image')) {
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            $product = Product::create($data);

            AdminAuditLog::log('create_product', "Menambahkan produk baru: {$product->name}");

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['game', 'category', 'images', 'orderItems' => function ($q) {
            $q->with('order')->orderBy('created_at', 'desc')->take(10);
        }]);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $games = Game::all();
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'games', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('main_image')) {
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            $product->update($data);

            AdminAuditLog::log('update_product', "Memperbarui produk: {$product->name}");

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $productName = $product->name;
            $product->delete();

            AdminAuditLog::log('delete_product', "Menghapus produk: {$productName}");

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
