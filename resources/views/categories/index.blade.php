<x-layouts.app :title="__('Quản lý danh mục')">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Header với thống kê --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Quản lý danh mục</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Quản lý và tổ chức các danh mục của bạn</p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-3 shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Tổng cộng</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $allCategories->total() }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-3 shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Danh mục cha</div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $parentOptions->count() }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-3 shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Danh mục con</div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $childOptions->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tìm kiếm và bộ lọc --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <form method="GET" action="{{ route('categories.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Tìm kiếm theo tên --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tìm kiếm</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                   placeholder="Nhập tên danh mục...">
                        </div>

                        {{-- Lọc theo tác giả --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tác giả</label>
                            <select name="author" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Tất cả tác giả</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author }}" {{ request('author') == $author ? 'selected' : '' }}>
                                        {{ $author }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lọc theo danh mục cha --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Danh mục cha</label>
                            <select name="parent_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Tất cả danh mục cha</option>
                                @foreach($parentOptions as $parent)
                                    <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lọc theo loại --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại danh mục</label>
                            <select name="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Tất cả loại</option>
                                <option value="parent" {{ request('type') == 'parent' ? 'selected' : '' }}>Danh mục cha</option>
                                <option value="child" {{ request('type') == 'child' ? 'selected' : '' }}>Danh mục con</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        {{-- Sắp xếp --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sắp xếp</label>
                            <select name="sort" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                        </div>

                        {{-- Nút áp dụng --}}
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Áp dụng bộ lọc
                            </button>
                        </div>

                        {{-- Nút xóa bộ lọc --}}
                        @if(request()->hasAny(['search', 'author', 'parent_id', 'type', 'sort']))
                            <div class="flex items-end">
                                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Xóa bộ lọc
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Form thêm/sửa danh mục --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ request('edit') ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới' }}
                        </h3>
                        @if(request('parent_id'))
                            @php
                                $parentCategory = \App\Models\Category::find(request('parent_id'));
                            @endphp
                            @if($parentCategory)
                                <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                    Đang thêm danh mục con cho: <strong>{{ $parentCategory->title }}</strong>
                                </p>
                            @endif
                        @endif
                    </div>
                    @if(request('edit'))
                        <a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                
                @php 
                    $editing = null;
                    if (request('edit')) {
                        $editing = \App\Models\Category::find(request('edit'));
                    }
                @endphp
                <form method="POST" action="{{ $editing ? route('categories.update', $editing) : route('categories.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    @csrf
                    @if($editing) @method('PUT') @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tiêu đề *</label>
                        <input name="title" value="{{ old('title', $editing->title ?? '') }}" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                               placeholder="Nhập tiêu đề danh mục" />
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tác giả</label>
                        <input name="author_name" value="{{ old('author_name', $editing->author_name ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                               placeholder="Nhập tên tác giả" />
                        @error('author_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mô tả</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                  placeholder="Nhập mô tả danh mục">{{ old('description', $editing->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Danh mục cha</label>
                        <select name="parent_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Chọn danh mục cha (để trống nếu là danh mục gốc)</option>
                            @foreach($parentOptions as $parent)
                                <option value="{{ $parent->id }}" 
                                        {{ old('parent_id', $editing->parent_id ?? request('parent_id')) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hình ảnh banner</label>
                        <input type="file" name="banner_image" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
                        @error('banner_image')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        
                        @if($editing && $editing->banner_image_url)
                            <div class="mt-2">
                                <img src="{{ $editing->banner_image_url }}" alt="Banner hiện tại" class="h-20 w-32 object-cover rounded-md">
                                <p class="text-xs text-gray-500 mt-1">Hình ảnh hiện tại</p>
                            </div>
                        @endif
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Thư viện ảnh (tối đa 10 ảnh)</label>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
                        @error('gallery_images')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('gallery_images.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        
                        @if($editing && $editing->gallery_images_count > 0)
                            <div class="mt-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Thư viện ảnh hiện tại ({{ $editing->gallery_images_count }} ảnh):</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($editing->gallery_image_urls as $imageUrl)
                                        <img src="{{ $imageUrl }}" alt="Gallery image" class="h-16 w-16 object-cover rounded-md">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="md:col-span-2 flex gap-3 pt-4">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $editing ? 'Cập nhật danh mục' : 'Thêm danh mục mới' }}
                        </button>
                        @if($editing)
                            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Hủy bỏ
                            </a>
                        @endif
                    </div>
                </form>
                
                {{-- Nút thêm mới ngay dưới form --}}
                @if(!request('edit'))
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('categories.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Thêm mới
                        </a>
                    </div>
                @endif
            </div>

            {{-- Bảng danh sách danh mục --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if(session('success'))
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Danh sách danh mục</h3>
                </div>

                @if($allCategories->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hình ảnh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thông tin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tác giả</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Danh mục cha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày tạo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($allCategories as $category)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            #{{ $category->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                @if($category->banner_image_url)
                                                    <img src="{{ $category->banner_image_url }}" alt="Banner" class="h-10 w-16 object-cover rounded-md">
                                                @else
                                                    <div class="h-10 w-16 bg-gray-200 dark:bg-gray-600 rounded-md flex items-center justify-center">
                                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                @if($category->gallery_images_count > 0)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        +{{ $category->gallery_images_count }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->title }}</div>
                                            @if($category->description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($category->description, 50) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">ID: {{ $category->id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $category->author_name ?? 'Chưa có' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($category->parent)
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $category->parent->title }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $category->parent->id }}</div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Danh mục gốc
                                                </span>
                                                @if($category->children_count > 0)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $category->children_count }} danh mục con</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $category->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                @if(!$category->parent)
                                                    <a href="{{ route('categories.index', ['parent_id' => $category->id]) }}" 
                                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800 rounded-md transition-colors">
                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        Thêm con
                                                    </a>
                                                    @if($category->children_count > 0)
                                                        <a href="{{ route('categories.index', ['parent_id' => $category->id]) }}" 
                                                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:text-purple-200 dark:hover:bg-purple-800 rounded-md transition-colors">
                                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            Xem con
                                                        </a>
                                                    @endif
                                                @endif
                                                <a href="{{ route('categories.index', ['edit' => $category->id]) }}" 
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 rounded-md transition-colors">
                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Sửa
                                                </a>
                                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa danh mục &quot;{{ $category->title }}&quot;?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800 rounded-md transition-colors">
                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($allCategories->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $allCategories->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Không có danh mục nào</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bắt đầu bằng cách tạo danh mục đầu tiên của bạn.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>