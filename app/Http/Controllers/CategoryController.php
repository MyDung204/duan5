<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách tác giả (nếu có cột author_name)
        $authors = Category::select('author_name')
            ->whereNotNull('author_name')
            ->distinct()
            ->pluck('author_name');

        // Lấy danh mục cha và con
        $parentOptions = Category::whereNull('parent_id')->get();
        $childOptions = Category::whereNotNull('parent_id')->get();

        // Query builder cho danh mục với các filter
        $query = Category::with(['parent', 'children']);

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        // Lọc theo tác giả
        if ($request->filled('author')) {
            $query->where('author_name', $request->input('author'));
        }

        // Lọc theo danh mục cha
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->input('parent_id'));
        }

        // Lọc theo loại danh mục
        if ($request->filled('type')) {
            if ($request->input('type') === 'parent') {
                $query->whereNull('parent_id');
            } elseif ($request->input('type') === 'child') {
                $query->whereNotNull('parent_id');
            }
        }

        // Sắp xếp
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Phân trang
        $categories = $query->paginate(15)->appends($request->query());

        // Lấy danh sách bài viết (nếu có model Post)
        $allPosts = Post::with('category')->orderBy('created_at', 'desc')->get();

        // Truyền toàn bộ biến sang view
        return view('categories.index', compact(
            'authors',
            'categories',
            'allPosts',
            'parentOptions',
            'childOptions'
        ));
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        $posts = Post::where('category_id', $id)->get();

        return view('category.show', compact('category', 'posts'));
    }

    public function create()
    {
        $parentOptions = Category::whereNull('parent_id')->get();
        return view('category.create', compact('parentOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:categories,title',
            'author_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categories,id',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'title.required' => 'Tiêu đề danh mục là bắt buộc.',
            'title.unique' => 'Tiêu đề danh mục đã tồn tại.',
            'title.max' => 'Tiêu đề danh mục không được vượt quá 255 ký tự.',
            'author_name.max' => 'Tên tác giả không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
            'banner_image.image' => 'File banner phải là hình ảnh.',
            'banner_image.mimes' => 'Banner chỉ hỗ trợ định dạng: jpeg, png, jpg, gif, webp.',
            'banner_image.max' => 'Banner không được vượt quá 2MB.',
            'gallery_images.max' => 'Tối đa 10 ảnh trong thư viện.',
            'gallery_images.*.image' => 'Tất cả file trong thư viện phải là hình ảnh.',
            'gallery_images.*.mimes' => 'Thư viện chỉ hỗ trợ định dạng: jpeg, png, jpg, gif, webp.',
            'gallery_images.*.max' => 'Mỗi ảnh trong thư viện không được vượt quá 2MB.',
        ]);

        $category = new Category();
        $category->title = $request->input('title');
        $category->author_name = $request->input('author_name');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id');

        // Upload banner image
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('categories/banners', 'public');
            $category->banner_image_path = $bannerPath;
        }

        // Upload gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('categories/gallery', 'public');
                $galleryPaths[] = $path;
            }
            $category->gallery_image_path = json_encode($galleryPaths);
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentOptions = Category::whereNull('parent_id')->get();
        return view('category.edit', compact('category', 'parentOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:categories,title,' . $id,
            'author_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categories,id',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'title.required' => 'Tiêu đề danh mục là bắt buộc.',
            'title.unique' => 'Tiêu đề danh mục đã tồn tại.',
            'title.max' => 'Tiêu đề danh mục không được vượt quá 255 ký tự.',
            'author_name.max' => 'Tên tác giả không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
            'banner_image.image' => 'File banner phải là hình ảnh.',
            'banner_image.mimes' => 'Banner chỉ hỗ trợ định dạng: jpeg, png, jpg, gif, webp.',
            'banner_image.max' => 'Banner không được vượt quá 2MB.',
            'gallery_images.max' => 'Tối đa 10 ảnh trong thư viện.',
            'gallery_images.*.image' => 'Tất cả file trong thư viện phải là hình ảnh.',
            'gallery_images.*.mimes' => 'Thư viện chỉ hỗ trợ định dạng: jpeg, png, jpg, gif, webp.',
            'gallery_images.*.max' => 'Mỗi ảnh trong thư viện không được vượt quá 2MB.',
        ]);

        $category = Category::findOrFail($id);
        $category->title = $request->input('title');
        $category->author_name = $request->input('author_name');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id');

        // Upload banner image
        if ($request->hasFile('banner_image')) {
            // Xóa banner cũ nếu có
            if ($category->banner_image_path) {
                Storage::disk('public')->delete($category->banner_image_path);
            }
            $bannerPath = $request->file('banner_image')->store('categories/banners', 'public');
            $category->banner_image_path = $bannerPath;
        }

        // Upload gallery images
        if ($request->hasFile('gallery_images')) {
            // Xóa gallery cũ nếu có
            if ($category->gallery_image_path) {
                $oldGalleryPaths = json_decode($category->gallery_image_path, true);
                foreach ($oldGalleryPaths as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('categories/gallery', 'public');
                $galleryPaths[] = $path;
            }
            $category->gallery_image_path = json_encode($galleryPaths);
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Xóa banner image nếu có
        if ($category->banner_image_path) {
            Storage::disk('public')->delete($category->banner_image_path);
        }
        
        // Xóa gallery images nếu có
        if ($category->gallery_image_path) {
            $galleryPaths = json_decode($category->gallery_image_path, true);
            foreach ($galleryPaths as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
