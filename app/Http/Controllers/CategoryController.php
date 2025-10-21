<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục và form.
     */
    public function index(Request $request)
    {
        $categories = Category::with('user:id,name', 'children')->latest()->paginate(10);
        $authors = User::oldest('name')->get(['id', 'name']);
        $allCategories = Category::orderBy('title')->get(['id', 'title']);
        $parentOptions = Category::whereNull('parent_id')->orderBy('title')->get();
        $childOptions = [];

        if ($request->has('parent_id') && $request->parent_id) {
            $childOptions = Category::where('parent_id', $request->parent_id)->orderBy('title')->get();
        }

        return view('quanlydanhmuc', compact(
            'categories', 'authors', 'allCategories', 'parentOptions', 'childOptions'
        ));
    }

    /**
     * Lưu một danh mục mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100|unique:categories,title',
            'author_name' => 'required|string|max:50',
            'short_description' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:5000',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        
        if ($request->hasFile('banner_image')) {
            $validated['banner_image_path'] = $request->file('banner_image')->store('images/banners', 'public');
        }

        if ($request->hasFile('gallery_image')) {
            $validated['gallery_image_path'] = $request->file('gallery_image')->store('images/galleries', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        Category::create($validated);

        return redirect()->route('categories.index')->with('status', 'Thêm danh mục mới thành công!');
    }

    /**
     * Cập nhật một danh mục đã có.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category->id)],
            'author_name' => 'required|string|max:50',
            'short_description' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:5000',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($category->banner_image_path) File::delete(storage_path('app/public/' . $category->banner_image_path));
            $validated['banner_image_path'] = $request->file('banner_image')->store('images/banners', 'public');
        }
        if ($request->hasFile('gallery_image')) {
            if ($category->gallery_image_path) File::delete(storage_path('app/public/' . $category->gallery_image_path));
            $validated['gallery_image_path'] = $request->file('gallery_image')->store('images/galleries', 'public');
        }
        
        $validated['slug'] = Str::slug($validated['title']);
        $category->update($validated);
        
        return redirect()->route('categories.index')->with('status', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa một danh mục.
     */
    public function destroy(Category $category)
    {
        if ($category->banner_image_path) {
            File::delete(storage_path('app/public/' . $category->banner_image_path));
        }
        if ($category->gallery_image_path) {
            File::delete(storage_path('app/public/' . $category->gallery_image_path));
        }
        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Xóa danh mục thành công!');
    }
}