<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Thêm 2 cột mới
            $table->string('banner_image_path')->nullable()->after('content');
            $table->string('gallery_image_path')->nullable()->after('banner_image_path');

            // Xóa cột cũ
            $table->dropColumn('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Hành động để rollback nếu cần
            $table->dropColumn(['banner_image_path', 'gallery_image_path']);
            $table->string('image_path')->nullable();
        });
    }
};