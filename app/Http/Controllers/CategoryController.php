<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryRepository $repository)
    {
    }

    public function index(Request $request)
    {
        $categories = $this->repository->paginate(15, $request->only('search'));
        return view('categories.index', compact('categories'));
    }


    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        $this->repository->create($data);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }


    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $this->repository->update($category->id, $data);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk.');
        }
        $this->repository->delete($category->id);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
