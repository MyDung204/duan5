<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController; // THÊM DÒNG NÀY
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [CategoryController::class, 'index'])->name('home');

// Trang dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Nhóm route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    // ----- Settings -----
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // ----- Categories CRUD -----
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // ----- Posts CRUD (CẬP NHẬT Ở ĐÂY) -----
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    // Bạn có thể thêm route get('/posts', ...) nếu muốn có một trang riêng để quản lý bài viết
});

// Dòng require auth.php đã bị xóa/comment đi là chính xác