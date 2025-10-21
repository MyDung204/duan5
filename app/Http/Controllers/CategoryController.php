<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
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
        // Lấy danh sách danh mục đã phân trang
        $categories = Category::with('user:id,name', 'children', 'posts')
            ->when($request->has('q'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->q . '%');
            })
            ->latest()->paginate(10);

        // Lấy dữ liệu cho các dropdown filter và form
        $authors = User::oldest('name')->get(['id', 'name']);
        $allCategories = Category::orderBy('title')->get(['id', 'title']);
        $allPosts = Post::orderBy('title')->get(['id', 'title']);
        $parentOptions = Category::whereNull('parent_id')->orderBy('title')->get();
        $childOptions = [];

        if ($request->has('parent_id') && $request->parent_id) {
            $childOptions = Category::where('parent_id', $request->parent_id)->orderBy('title')->get();
        }

        return view('quanlyduan', compact(
            'categories',
            'authors',
            'allCategories',
            'allPosts',
            'parentOptions',
            'childOptions'
        ));
    }

    /**
     * Lưu một danh mục mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:categories,title',
            'author_name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'post_ids' => 'nullable|array',
            'post_ids.*' => 'exists:posts,id',
        ]);

        // 2. Xử lý upload ảnh (nếu có)
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('images', 'public');
            $validated['image_path'] = $imagePath;
        }

        // 3. Tạo slug từ title
        $validated['slug'] = Str::slug($validated['title']);

        // 4. Tạo mới Category và lưu
        $category = Category::create($validated);

        // 5. Gắn các bài viết vào danh mục (nếu có)
        if ($request->has('post_ids')) {
            $category->posts()->sync($validated['post_ids']);
        }

        // 6. Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('categories.index')->with('status', 'Thêm danh mục mới thành công!');
    }

    /**
     * Cập nhật một danh mục đã có.
     */
    public function update(Request $request, Category $category)
    {
        // Validate dữ liệu, cho phép title trùng với chính nó
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'author_name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'post_ids' => 'nullable|array',
            'post_ids.*' => 'exists:posts,id',
        ]);

        // Xử lý upload ảnh mới
        if ($request->hasFile('image_path')) {
            // Xóa ảnh cũ nếu có
            if ($category->image_path) {
                File::delete(storage_path('app/public/' . $category->image_path));
            }
            $imagePath = $request->file('image_path')->store('images', 'public');
            $validated['image_path'] = $imagePath;
        }

        // Cập nhật slug nếu title thay đổi
        $validated['slug'] = Str::slug($validated['title']);

        // Cập nhật thông tin
        $category->update($validated);

        // Cập nhật lại danh sách bài viết
        $category->posts()->sync($request->post_ids ?? []);

        return redirect()->route('categories.index')->with('status', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa một danh mục.
     */
    public function destroy(Category $category)
    {
        // Xóa ảnh liên quan nếu có
        if ($category->image_path) {
            File::delete(storage_path('app/public/' . $category->image_path));
        }

        // Xóa danh mục
        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Xóa danh mục thành công!');
    }
}