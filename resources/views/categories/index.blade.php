<x-layouts.app :title="__('Quản lý Danh mục')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        {{-- Header với thống kê và tìm kiếm --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col gap-4">
                {{-- Header với tiêu đề --}}
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Quản lý Danh mục</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Tổ chức và quản lý các danh mục của bạn một cách hiệu quả</p>
                </div>
                
                {{-- Thống kê nhanh --}}
                <div class="flex gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-3 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Tổng danh mục</div>
                        <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $categories->total() }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 px-4 py-3 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">Danh mục cha</div>
                        <div class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $parentOptions->count() }}</div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 px-4 py-3 rounded-lg border border-purple-200 dark:border-purple-800">
                        <div class="text-sm text-purple-600 dark:text-purple-400 font-medium">Danh mục con</div>
                        <div class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ $childOptions->count() }}</div>
                    </div>
                </div>
            </div>
            
            {{-- Thanh tìm kiếm và lọc --}}
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Tìm kiếm theo tên danh mục..." 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <select name="author" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả tác giả</option>
                            @foreach($authors as $author)
                                <option value="{{ $author }}" {{ request('author') === $author ? 'selected' : '' }}>{{ $author }}</option>
                            @endforeach
                        </select>
                        
                        <select name="parent_id" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả danh mục cha</option>
                            @foreach($parentOptions as $parent)
                                <option value="{{ $parent->id }}" {{ (string)request('parent_id') === (string)$parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                            @endforeach
                        </select>
                        
                        <select name="type" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả loại</option>
                            <option value="parent" {{ request('type') === 'parent' ? 'selected' : '' }}>Danh mục cha</option>
                            <option value="child" {{ request('type') === 'child' ? 'selected' : '' }}>Danh mục con</option>
                        </select>
                        
                        <select name="sort" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        </select>
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Lọc
                        </button>
                        
                        @if(request()->hasAny(['search', 'author', 'parent_id', 'sort']))
                            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Xóa bộ lọc
                            </a>
                        @endif
                    </div>
                </form>
            </div>
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
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mô tả</label>
                            <textarea name="description" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white resize-none" 
                                      placeholder="Nhập mô tả chi tiết về danh mục">{{ old('description', $editing->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Danh mục cha</label>
                            <select name="parent_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Không có danh mục cha</option>
                                @foreach($parentOptions as $parent)
                                    @if(!$editing || $editing->id !== $parent->id)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $editing->parent_id ?? request('parent_id') ?? '') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->title }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Banner ảnh</label>
                            <input type="file" name="banner_image" accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300" />
                            @if($editing && $editing->banner_image_path)
                                <div class="mt-2">
                                    <img src="{{ $editing->banner_image_url }}" alt="Banner hiện tại" class="h-20 w-full object-cover rounded-md">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Banner hiện tại</p>
                                </div>
                            @endif
                            @error('banner_image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Thư viện ảnh (tối đa 10 ảnh)</label>
                            <input type="file" name="gallery_images[]" accept="image/*" multiple
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900 dark:file:text-green-300" />
                            @if($editing && $editing->gallery_image_path)
                                <div class="mt-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($editing->gallery_image_urls as $imageUrl)
                                            <img src="{{ $imageUrl }}" alt="Gallery image" class="h-16 w-full object-cover rounded-md">
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $editing->gallery_images_count }} ảnh hiện tại</p>
                                </div>
                            @endif
                            @error('gallery_images')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('gallery_images.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
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
                        <div class="bg-green-50 dark:bg-green-900/20 border-b border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif
                    
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Danh sách danh mục</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Hiển thị {{ $categories->count() }} trong tổng số {{ $categories->total() }} danh mục</p>
                            </div>
                            
                            {{-- Thông báo khi đang lọc theo danh mục cha --}}
                            @if(request('parent_id'))
                                @php
                                    $parentCategory = \App\Models\Category::find(request('parent_id'));
                                @endphp
                                @if($parentCategory)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-blue-600 dark:text-blue-400">
                                            Đang xem danh mục con của: <strong>{{ $parentCategory->title }}</strong>
                                        </span>
                                        <a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hình ảnh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thông tin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tác giả</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Danh mục cha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày tạo</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col items-center space-y-2">
                                                @if($category->banner_image_path)
                                                    <img src="{{ $category->banner_image_url }}" alt="{{ $category->title }}" class="h-16 w-24 object-cover rounded-md border border-gray-200 dark:border-gray-600">
                                                @else
                                                    <div class="h-16 w-24 bg-gray-100 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                @if($category->gallery_images_count > 0)
                                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                        </svg>
                                                        {{ $category->gallery_images_count }} ảnh
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->title }}</div>
                                                @if($category->description)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($category->description, 60) }}</div>
                                                @endif
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">#{{ $category->id }}</div>
                                                
                                                {{-- Hiển thị danh mục con nếu có --}}
                                                @if($category->children->count() > 0)
                                                    <div class="mt-2">
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Danh mục con:</div>
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($category->children->take(3) as $child)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                                    {{ $child->title }}
                                                                </span>
                                                            @endforeach
                                                            @if($category->children->count() > 3)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                                    +{{ $category->children->count() - 3 }} khác
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($category->author_name)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $category->author_name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($category->parent)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $category->parent->title }}
                                                </span>
                                            @else
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-gray-400 dark:text-gray-500">Danh mục gốc</span>
                                                    @if($category->children->count() > 0)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            {{ $category->children->count() }} con
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $category->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex gap-1 justify-end">
                                                {{-- Nút thêm con (chỉ hiển thị cho danh mục cha) --}}
                                                @if(!$category->parent_id)
                                                    <a href="{{ route('categories.index', ['parent_id' => $category->id]) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-medium rounded-md transition-colors border border-green-200 hover:border-green-300" 
                                                       title="Thêm danh mục con">
                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        Thêm con
                                                    </a>
                                                    
                                                    {{-- Nút xem danh mục con --}}
                                                    @if($category->children->count() > 0)
                                                        <a href="{{ route('categories.index', ['type' => 'child', 'parent_id' => $category->id]) }}" 
                                                           class="inline-flex items-center px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-800 text-xs font-medium rounded-md transition-colors border border-purple-200 hover:border-purple-300" 
                                                           title="Xem danh mục con">
                                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            Xem con
                                                        </a>
                                                    @endif
                                                @endif
                                                
                                                {{-- Nút sửa --}}
                                                <a href="{{ route('categories.index', ['edit' => $category->id]) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-md transition-colors border border-blue-200 hover:border-blue-300"
                                                   title="Sửa danh mục">
                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Sửa
                                                </a>
                                                
                                                {{-- Nút xóa --}}
                                                <form method="POST" action="{{ route('categories.destroy', $category) }}" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa danh mục &quot;{{ $category->title }}&quot;?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-md transition-colors border border-red-200 hover:border-red-300"
                                                            title="Xóa danh mục">
                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Không có danh mục nào</h3>
                                                <p class="text-gray-500 dark:text-gray-400 mb-4">Hãy thêm danh mục đầu tiên của bạn</p>
                                                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Thêm danh mục
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Phân trang --}}
                    @if($categories->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $categories->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
        </div>
    </div>
</x-layouts.app>
