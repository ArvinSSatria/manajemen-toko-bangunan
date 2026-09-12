<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Store;
use App\Models\Bo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'bo'])->withCount('inventories');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('product_code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('bo_id')) {
            if ($request->bo_id === 'own') {
                $query->where('is_consignment', false);
            } else {
                $query->where('is_consignment', true)->where('bo_id', $request->bo_id);
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();
        $bos = Bo::orderBy('name')->get();
        $stores = Store::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'bos', 'stores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_code' => 'required|string|max:100|unique:products,product_code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'is_consignment' => 'nullable|boolean',
            'bo_id' => 'nullable|required_if:is_consignment,1|exists:bo,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'initial_stock' => 'nullable|array',
            'initial_stock.*' => 'nullable|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
        ]);

        $data['is_consignment'] = $request->has('is_consignment');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Create inventory for each store
        $stores = Store::where('is_active', true)->get();
        foreach ($stores as $store) {
            Inventory::create([
                'store_id' => $store->id,
                'product_id' => $product->id,
                'stock' => $request->input("initial_stock.{$store->id}", 0),
                'minimum_stock' => $request->input('minimum_stock', 10),
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'product_code' => 'required|string|max:100|unique:products,product_code,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'is_consignment' => 'nullable|boolean',
            'bo_id' => 'nullable|required_if:is_consignment,1|exists:bo,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data['is_consignment'] = $request->has('is_consignment');

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
