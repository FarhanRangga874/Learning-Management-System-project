<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request; 
use Illuminate\Support\Str; 
use Illuminate\Routing\Controller; // Pastikan ini mengarah ke Controller induk

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data kategori dari database
        $categories = Category::orderBy('id', 'desc')->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // 2. Buat slug otomatis dari name
        $validated['slug'] = Str::slug($request->name);

        // 3. Simpan ke database
        Category::create($validated);

        // 4. Redirect
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Tidak dipakai untuk sekarang
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // 1. Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 2. Update Slug jika nama berubah
        $validated['slug'] = Str::slug($request->name);

        // 3. Update data
        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}