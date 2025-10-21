<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    {{-- Tích hợp Tailwind CSS qua CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Tích hợp Font Awesome cho biểu tượng --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: #e2e8f0;
        }

        .glass-effect {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(51, 65, 85, 0.3);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .table-custom th,
        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
            border-color: rgba(51, 65, 85, 0.3);
        }

        .table-custom {
            background: rgba(30, 41, 59, 0.6);
        }

        .table-custom thead th {
            background: rgba(51, 65, 85, 0.4);
            color: #e2e8f0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-custom tbody tr {
            transition: all 0.3s ease;
        }

        .table-custom tbody tr:hover {
            background: rgba(51, 65, 85, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-success {
            background: linear-gradient(45deg, #10b981, #059669);
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(45deg, #6b7280, #4b5563);
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
            color: white;
        }

        .form-control {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(51, 65, 85, 0.5);
            color: #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(30, 41, 59, 0.9);
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .form-control::placeholder {
            color: rgba(226, 232, 240, 0.6);
        }
        
        /* CSS cho input file tùy chỉnh */
        input[type="file"].form-control {
            padding: 0.375rem 0.75rem;
        }
        input[type="file"]::file-selector-button {
            background: linear-gradient(45deg, #6b7280, #4b5563);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }
        input[type="file"]::file-selector-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(107, 114, 128, 0.4);
        }

        .card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(51, 65, 85, 0.3);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .nav-link {
            color: rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #e2e8f0;
            transform: translateY(-1px);
        }

        .badge {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
        }

        .pagination .page-link {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(51, 65, 85, 0.5);
            color: #e2e8f0;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: rgba(51, 65, 85, 0.6);
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            border-color: #3b82f6;
            color: white;
        }

        .alert {
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(51, 65, 85, 0.5);
            color: #e2e8f0;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.4);
            color: #10b981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        .text-gradient {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #1d4ed8, #3b82f6);
        }

        /* Layout adjustments - Compact */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 2rem; /* Tăng khoảng cách giữa 2 cột */
            align-items: start;
        }

        @media (max-width: 768px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="text-gray-200 font-sans">
    <nav class="glass-effect p-4 sticky top-0 z-10">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gradient">
                <i class="fas fa-layer-group mr-2"></i>
                Quản lý danh mục
            </h1>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="nav-link">
                    <i class="fas fa-home mr-1"></i>Trang chủ
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-newspaper mr-1"></i>Bài viết
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-tags mr-1"></i>Danh mục
                </a>
                <div class="relative">
                    <button class="nav-link flex items-center">
                        <i class="fas fa-user-circle mr-2"></i>
                        Mỹ Dung
                        <i class="fas fa-chevron-down ml-1"></i>
                    </button>
                </div>
            </div>
            <button class="md:hidden text-gray-200 hover:text-gray-400 transition-colors">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </nav>

    <main class="container mx-auto p-4 md:p-8">
        <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden fade-in p-4 md:p-6">

            @if(session('status'))
                <div class="alert alert-success mb-4 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif
            
            <div class="main-layout">
                <div>
                    <h2 class="text-lg font-bold text-gradient mb-3">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Thêm/Sửa danh mục
                    </h2>
                    
                    @php $editing = request('edit') ? \App\Models\Category::with('posts')->find(request('edit')) : null; @endphp
                    <form method="POST" action="{{ $editing ? route('categories.update', $editing) : route('categories.store') }}" class="space-y-4" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @if($editing)
                            @method('PUT')
                        @endif
                        
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">
                                    <i class="fas fa-tag mr-1"></i>Tiêu đề
                                </label>
                                <input name="title" value="{{ old('title', $editing->title ?? '') }}" required class="form-control rounded-lg py-2 px-4 w-full" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">
                                    <i class="fas fa-user mr-1"></i>Tác giả
                                </label>
                                <input name="author_name" value="{{ old('author_name', $editing->author_name ?? '') }}" required class="form-control rounded-lg py-2 px-4 w-full" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">
                                    <i class="fas fa-align-left mr-1"></i>Mô tả ngắn
                                </label>
                                <input name="short_description" value="{{ old('short_description', $editing->short_description ?? '') }}" class="form-control rounded-lg py-2 px-4 w-full" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">
                                    <i class="fas fa-file-text mr-1"></i>Nội dung
                                </label>
                                <textarea name="content" rows="3" class="form-control rounded-lg py-2 px-4 w-full">{{ old('content', $editing->content ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">
                                    <i class="fas fa-image mr-1"></i>Chọn ảnh từ máy
                                </label>
                                <input type="file" name="image_path" class="form-control rounded-lg w-full" accept="image/*" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">
                                    <i class="fas fa-sitemap mr-1"></i>Danh mục cha
                                </label>
                                <select name="parent_id" class="form-control rounded-lg py-2 px-4 w-full">
                                    <option value="">Không</option>
                                    @foreach($allCategories as $c)
                                        <option value="{{ $c->id }}" {{ old('parent_id', $editing->parent_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">
                                    <i class="fas fa-link mr-1"></i>Gắn bài viết
                                </label>
                                <select multiple name="post_ids[]" class="form-control rounded-lg py-2 px-4 w-full min-h-24">
                                    @php $selectedPosts = collect(old('post_ids', $editing?->posts->pluck('id')->all() ?? [])); @endphp
                                    @foreach($allPosts as $p)
                                        <option value="{{ $p->id }}" {{ $selectedPosts->contains($p->id) ? 'selected' : '' }}>{{ $p->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 pt-3">
                            <button type="submit" class="btn {{ $editing ? 'btn-warning' : 'btn-primary' }} flex-1">
                                <i class="fas {{ $editing ? 'fa-save' : 'fa-plus' }} mr-2"></i>
                                {{ $editing ? 'Cập nhật' : 'Thêm mới' }}
                            </button>
                            @if($editing)
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i>Hủy
                                </a>
                            @endif
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="mt-3 alert alert-danger rounded-lg">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="flex flex-wrap gap-2 items-center justify-between mb-3">
                        <h2 class="text-lg font-bold text-gradient">
                            <i class="fas fa-list mr-2"></i>
                            Danh sách danh mục
                        </h2>
                        
                        <form method="GET" action="{{ route('categories.index') }}" class="flex flex-wrap items-center gap-2">
                            <div class="relative">
                                <select name="author" class="form-control rounded-lg py-2 pl-4 w-auto">
                                    <option value="">Tất cả tác giả</option>
                                    @foreach($authors as $au)
                                        <option value="{{ $au }}" {{ request('author')===$au ? 'selected' : '' }}>{{ $au }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <select name="parent_id" class="form-control rounded-lg py-2 pl-4 w-auto">
                                    <option value="">Danh mục cha: -- Tất cả --</option>
                                    @foreach($parentOptions as $opt)
                                        <option value="{{ $opt->id }}" {{ (string)request('parent_id')===(string)$opt->id ? 'selected' : '' }}>{{ $opt->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <select name="child_id" class="form-control rounded-lg py-2 pl-4 w-auto">
                                    <option value="">Danh mục con: -- Tất cả --</option>
                                    @foreach($childOptions as $opt)
                                        <option value="{{ $opt->id }}" {{ (string)request('child_id')===(string)$opt->id ? 'selected' : '' }}>{{ $opt->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm..." class="form-control rounded-lg py-2 pl-12 pr-4 w-48">
                            </div>
                            <div class="relative">
                                <select name="sort" class="form-control rounded-lg py-2 pl-4 w-auto">
                                    <option value="title_asc" {{ request('sort','title_asc')==='title_asc'?'selected':'' }}>A → Z</option>
                                    <option value="title_desc" {{ request('sort')==='title_desc'?'selected':'' }}>Z → A</option>
                                    <option value="posts_desc" {{ request('sort')==='posts_desc'?'selected':'' }}>Nội dung nhiều → ít</option>
                                    <option value="posts_asc" {{ request('sort')==='posts_asc'?'selected':'' }}>Nội dung ít → nhiều</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter mr-2"></i>Lọc
                            </button>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left table-custom">
                            <thead class="border-b border-gray-600 text-sm text-gray-300 uppercase">
                            <tr>
                                <th scope="col" class="w-14">ID</th>
                                <th scope="col" class="min-w-[150px]">
                                    Tên danh mục
                                </th>
                                <th scope="col" class="min-w-[200px]">
                                    Mô tả
                                </th>
                                <th scope="col" class="w-32">
                                    Tác giả
                                </th>
                                <th scope="col" class="w-32">
                                    Ảnh
                                </th>
                                <th scope="col" class="w-32">
                                    Danh mục con
                                </th>
                                <th scope="col" class="w-32">
                                    Bài viết
                                </th>
                                <th scope="col" class="w-32">
                                    Thao tác
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr class="border-b border-gray-600 hover:bg-gray-700/30 transition-colors">
                                        <td class="text-gray-300">{{ $category->id }}</td>
                                        <td class="font-medium text-gray-200">
                                            <div class="flex items-center gap-2">
                                                <span>{{ $category->title }}</span>
                                                <span class="text-xs text-gray-400">(@if($category->parent_id) Con của: {{ optional($category->parent)->title }} @else Danh mục cha @endif)</span>
                                            </div>
                                        </td>
                                        <td class="text-gray-300 max-w-xs truncate">
                                            {{ $category->short_description }}
                                        </td>
                                        <td class="text-gray-300">{{ $category->author_name }}</td>
                                        <td>
                                            @if($category->image_path)
                                                <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->title }}" class="w-12 h-8 object-cover rounded">
                                            @else
                                                <span class="text-gray-400">Không có</span>
                                            @endif
                                        </td>
                                        <td class="text-gray-300">
                                            @if($category->children->count() > 0)
                                                <ul class="text-xs space-y-1">
                                                    @foreach($category->children as $child)
                                                        <li class="flex items-center gap-1">
                                                            <i class="fas fa-arrow-right text-gray-400"></i>
                                                            <span>{{ $child->title }}</span>
                                                            <span class="text-gray-400">({{ $child->posts_count }} bài)</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">Không có</span>
                                            @endif
                                        </td>
                                        <td class="text-gray-300">
                                            @if($category->posts->count() > 0)
                                                <ul class="text-xs space-y-1">
                                                    @foreach($category->posts->take(3) as $post)
                                                        <li class="flex items-center gap-1">
                                                            <i class="fas fa-file-alt text-gray-400"></i>
                                                            <span>{{ $post->title }}</span>
                                                        </li>
                                                    @endforeach
                                                    @if($category->posts->count() > 3)
                                                        <li class="text-gray-400">+{{ $category->posts->count() - 3 }} bài khác</li>
                                                    @endif
                                                </ul>
                                            @else
                                                <span class="text-gray-400">Không có</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('categories.index', ['edit' => $category->id]) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-10 text-gray-400">Không tìm thấy kết quả phù hợp.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 flex justify-center">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        // Hiệu ứng fade in cho các phần tử
        document.addEventListener('DOMContentLoaded', function() {
            // Thêm hiệu ứng fade in cho các hàng trong bảng
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Hiệu ứng hover cho các nút
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Hiệu ứng cho form inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.boxShadow = '0 0 0 0.2rem rgba(59, 130, 246, 0.25)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = 'none';
                });
            });

            // Hiệu ứng cho các card
            const cards = document.querySelectorAll('.glass-effect');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 8px 32px 0 rgba(0, 0, 0, 0.3)';
                });
            });

            // Hiệu ứng loading cho form submit
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
                        submitBtn.disabled = true;
                    }
                });
            });

            // Hiệu ứng cho alert messages
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(-100%)';
                setTimeout(() => {
                    alert.style.transition = 'all 0.5s ease';
                    alert.style.opacity = '1';
                    alert.style.transform = 'translateX(0)';
                }, 100);
            });

            // Auto-hide alerts sau 5 giây
            setTimeout(() => {
                alerts.forEach(alert => {
                    alert.style.transition = 'all 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                });
            }, 5000);
        });
    </script>



</body>

</html>