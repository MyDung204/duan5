<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ có thể giữ nguyên hoặc chuyển hướng đến dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// === SỬA Ở ĐÂY: THAY ĐỔI ROUTE DASHBOARD ===
// Route này sẽ trỏ đến CategoryController để hiển thị trang quản lý của bạn
Route::get('/dashboard', [CategoryController::class, ''])
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
    // Các route này vẫn cần thiết để xử lý form
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});