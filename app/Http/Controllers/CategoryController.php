<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $authors = Category::select('author_name')->distinct()->pluck('author_name');
        $allCategories = Category::orderBy('title')->get(['id', 'title']);
        $allPosts = Post::orderBy('title')->get(['id', 'title']);
        $parentOptions = Category::whereNull('parent_id')->orderBy('title')->get(['id', 'title']);

        $parentId = $request->input('parent_id');
        $childOptions = $parentId ? Category::where('parent_id', $parentId)->orderBy('title')->get(['id', 'title']) : collect();

        $query = Category::with('parent', 'children.posts', 'posts')->withCount('posts');

        $this->applyFilters($request, $query);
        $sort = $request->input('sort', 'title_asc');
        $this->applySorting($sort, $query);

        $categories = $query->paginate(10)->withQueryString();

        return view('quanlydanhmuc', compact(
            'categories', 'authors', 'allCategories', 'allPosts',
            'parentOptions', 'childOptions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'post_ids' => 'nullable|array',
            'post_ids.*' => 'integer|exists:posts,id',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('categories', 'public');
        }

        $category = Category::create($validated);

        if (!empty($validated['post_ids'])) {
            $category->posts()->sync($validated['post_ids']);
        }

        return redirect()->route('categories.index')->with('status', 'Tạo danh mục thành công');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'post_ids' => 'nullable|array',
            'post_ids.*' => 'integer|exists:posts,id',
        ]);

        if ($request->hasFile('image_path')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('categories', 'public');
        }

        $category->update($validated);

        $category->posts()->sync($validated['post_ids'] ?? []);

        return redirect()->route('categories.index')->with('status', 'Cập nhật danh mục thành công');
    }

    public function destroy(Category $category)
    {
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->posts()->detach();
        $category->delete();
        
        return redirect()->route('categories.index')->with('status', 'Đã xóa danh mục');
    }

    private function applyFilters(Request $request, $query)
    {
        if ($q = $request->input('q')) {
            $query->where(fn($subQuery) => $subQuery->where('title', 'like', "%{$q}%")->orWhere('short_description', 'like', "%{$q}%"));
        }
        if ($author = $request->input('author')) {
            $query->where('author_name', $author);
        }
        if ($parentId = $request->input('parent_id')) {
            $query->where('parent_id', $parentId)->orWhere('id', $parentId);
        }
    }
    
    private function applySorting($sort, $query)
    {
        switch ($sort) {
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'posts_desc':
                $query->orderBy('posts_count', 'desc');
                break;
            case 'posts_asc':
                $query->orderBy('posts_count', 'asc');
                break;
            default:
                $query->orderBy('title', 'asc');
        }
    }
}