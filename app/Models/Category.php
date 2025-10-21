<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_name',
        'short_description',
        'content',
        'banner_image_path',
        'gallery_image_path',
    ];

    /**
     * Accessor: Lấy URL ảnh banner
     */
    public function getBannerImageUrlAttribute()
    {
        if ($this->banner_image_path) {
            return asset('storage/' . $this->banner_image_path);
        }
        return null;
    }

    /**
     * Accessor: Lấy URLs của thư viện ảnh
     */
    public function getGalleryImageUrlsAttribute()
    {
        if ($this->gallery_image_path) {
            $paths = json_decode($this->gallery_image_path, true);
            if (is_array($paths)) {
                return array_map(function($path) {
                    return asset('storage/' . $path);
                }, $paths);
            }
        }
        return [];
    }

    /**
     * Accessor: Đếm số ảnh trong thư viện
     */
    public function getGalleryImagesCountAttribute()
    {
        if ($this->gallery_image_path) {
            $paths = json_decode($this->gallery_image_path, true);
            return is_array($paths) ? count($paths) : 0;
        }
        return 0;
    }
}