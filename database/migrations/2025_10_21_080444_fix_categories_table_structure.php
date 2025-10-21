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
            // Đảm bảo có các cột cần thiết
            if (!Schema::hasColumn('categories', 'author_name')) {
                $table->string('author_name')->nullable();
            }
            if (!Schema::hasColumn('categories', 'banner_image_path')) {
                $table->string('banner_image_path')->nullable();
            }
            if (!Schema::hasColumn('categories', 'gallery_image_path')) {
                $table->text('gallery_image_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['author_name', 'banner_image_path', 'gallery_image_path']);
        });
    }
};