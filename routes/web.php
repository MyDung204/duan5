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

// === DASHBOARD ROUTE ===
// Route dashboard trở về bình thường
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Nhóm route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    // ----- Settings -----
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // ----- Categories CRUD -----
    Route::resource('categories', CategoryController::class)->except(['show']);
});