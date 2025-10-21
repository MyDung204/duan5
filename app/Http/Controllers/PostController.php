<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:posts,title',
            'author_name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        // 2. Xử lý upload ảnh (nếu có)
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('images', 'public');
            $validated['image_path'] = $imagePath;
        }

        // 3. Tạo slug
        $validated['slug'] = Str::slug($validated['title']);

        // 4. Lưu bài đăng
        $post = Post::create($validated);

        // 5. Gắn các danh mục (nếu có)
        if ($request->has('category_ids')) {
            $post->categories()->sync($validated['category_ids']);
        }

        // 6. Chuyển hướng về trang quanlyduan với thông báo
        return redirect()->route('home')->with('status', 'Tạo bài đăng thành công!');
    }
}