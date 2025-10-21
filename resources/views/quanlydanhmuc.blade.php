<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); background-attachment: fixed; min-height: 100vh; color: #cbd5e1; font-family: 'Inter', sans-serif; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        .glass-effect { background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(51, 65, 85, 0.3); }
        
        .form-control { background: rgba(15, 23, 42, 0.5); border: 1px solid #334155; color: #e2e8f0; transition: all 0.2s ease-in-out; }
        .form-control:focus { background: rgba(15, 23, 42, 0.8); border-color: #38bdf8; box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2); outline: none; }
        .form-control::placeholder { color: #64748b; }

        .btn { padding: 0.625rem 1.25rem; border-radius: 0.5rem; transition: all 0.2s ease-in-out; font-weight: 600; text-align: center; display: inline-flex; align-items: center; justify-content: center; }
        .btn-primary { background: linear-gradient(45deg, #38bdf8, #0ea5e9); border: none; color: white; box-shadow: 0 4px 15px rgba(56, 189, 248, 0.2); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(56, 189, 248, 0.3); }
        .btn-secondary { background: #334155; border: 1px solid #475569; color: #cbd5e1; }
        .btn-secondary:hover { background: #475569; }

        .table-custom th, .table-custom td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #334155; }
        .table-custom thead th { background: rgba(51, 65, 85, 0.2); text-transform: uppercase; font-size: 0.75rem; color: #94a3b8; letter-spacing: 0.05em; }
        .table-custom tbody tr { transition: background-color 0.2s ease-in-out; }
        .table-custom tbody tr:hover { background: rgba(51, 65, 85, 0.2); }
        
        .main-layout { display: grid; grid-template-columns: 420px 1fr; gap: 2rem; align-items: start; }
        @media (max-width: 1024px) { .main-layout { grid-template-columns: 1fr; } }
        
        .file-input { background: rgba(15, 23, 42, 0.5); border: 1px solid #334155; }
        .file-input::file-selector-button { background-color: #334155; color: #cbd5e1; font-weight: 500; border: none; padding: 0.5rem 1rem; margin-right: 1rem; border-radius: 0.375rem 0 0 0.375rem; cursor: pointer; transition: background-color 0.2s; }
        .file-input::file-selector-button:hover { background-color: #475569; }

        .pagination a, .pagination span { border: 1px solid #334155; background: #1e293b; padding: 0.5rem 0.75rem; }
        .pagination .active span { background: #0ea5e9; border-color: #0ea5e9; color: white; }
    </style>
</head>

<body class="text-gray-300">
    <main class="container mx-auto p-4 md:p-8">

        {{-- === BANNER VÀ THANH LỌC === --}}
        <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden p-6 mb-8">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <h1 class="text-2xl font-bold text-white">
                    Quản lý Danh mục
                </h1>
                <form method="GET" action="{{ route('categories.index') }}" class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm..." class="form-control rounded-lg py-2 pl-10 pr-4 w-48">
                    </div>
                    <select name="author" class="form-control rounded-lg py-2 px-4 w-auto">
                        <option value="">Tất cả tác giả</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->name }}" {{ request('author') === $author->name ? 'selected' : '' }}>{{ $author->name }}</option>
                        @endforeach
                    </select>
                    <select name="parent_id" class="form-control rounded-lg py-2 px-4 w-auto">
                        <option value="">Tất cả danh mục cha</option>
                        @foreach($parentOptions as $opt)
                            <option value="{{ $opt->id }}" {{ (string)request('parent_id') === (string)$opt->id ? 'selected' : '' }}>{{ $opt->title }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-2"></i>Lọc</button>
                </form>
            </div>
        </div>
        
        <div class="main-layout">
            {{-- === FORM THÊM/SỬA === --}}
            <div class="glass-effect rounded-2xl p-6">
                <h3 class="text-xl font-semibold mb-4 text-white">
                    <i class="fas {{ request('edit') ? 'fa-edit' : 'fa-plus-circle' }} mr-2 text-sky-400"></i>
                    {{ request('edit') ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới' }}
                </h3>
                @php $editing = request('edit') ? \App\Models\Category::find(request('edit')) : null; @endphp
                <form method="POST" action="{{ $editing ? route('categories.update', $editing) : route('categories.store') }}" class="space-y-4" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @if($editing) @method('PUT') @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Tiêu đề</label>
                        <input name="title" value="{{ old('title', $editing->title ?? '') }}" required class="form-control rounded-lg py-2 px-4 w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Tác giả</label>
                        <input name="author_name" value="{{ old('author_name', $editing->author_name ?? '') }}" required class="form-control rounded-lg py-2 px-4 w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Mô tả ngắn</label>
                        <textarea name="short_description" rows="2" class="form-control rounded-lg py-2 px-4 w-full">{{ old('short_description', $editing->short_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Nội dung</label>
                        <textarea name="content" rows="4" class="form-control rounded-lg py-2 px-4 w-full">{{ old('content', $editing->content ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Ảnh banner</label>
                        <input type="file" name="banner_image" class="file-input rounded-lg w-full text-sm text-gray-400" accept="image/*" />
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Thư viện ảnh</label>
                        <input type="file" name="gallery_image" class="file-input rounded-lg w-full text-sm text-gray-400" accept="image/*" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Danh mục cha</label>
                        <select name="parent_id" class="form-control rounded-lg py-2 px-4 w-full">
                            <option value="">Không</option>
                            @foreach($allCategories as $c)
                                @if(!$editing || $editing->id !== $c->id)
                                    <option value="{{ $c->id }}" {{ old('parent_id', $editing->parent_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="btn btn-primary flex-1">
                            <i class="fas {{ $editing ? 'fa-save' : 'fa-plus' }} mr-2"></i>
                            {{ $editing ? 'Cập nhật' : 'Thêm mới' }}
                        </button>
                        @if($editing)<a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy</a>@endif
                    </div>
                </form>
            </div>

            {{-- === BẢNG DANH SÁCH === --}}
            <div class="glass-effect rounded-2xl p-6">
                @if(session('status'))
                    <div class="alert-success mb-4 rounded-lg p-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('status') }}
                    </div>
                @endif
                <h3 class="text-xl font-semibold mb-4 text-white">Danh sách danh mục</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-custom">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Tác giả</th>
                                <th class="text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr class="border-b-transparent">
                                    <td class="font-mono text-sm text-gray-500">{{ $category->id }}</td>
                                    <td>
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-white">{{ $category->title }}</span>
                                            <span class="text-xs text-gray-400">{{ Str::limit($category->short_description, 50) }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $category->author_name }}</td>
                                    <td class="text-right">
                                        <div class="flex gap-4 justify-end">
                                            <a href="{{ route('categories.index', ['edit' => $category->id, 'page' => $categories->currentPage()]) }}" class="text-sky-400 hover:text-sky-300 font-semibold">Sửa</a>
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 font-semibold">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-8 text-gray-500">Không có danh mục nào.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                     <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>


</html>