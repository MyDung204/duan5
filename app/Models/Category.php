<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'slug',
        'author_name',
        'short_description',
        'content',
        'banner_image_path',
        'gallery_image_path',
        'parent_id',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }
    
    /**
     * THÊM HÀM NÀY VÀO
     * Định nghĩa mối quan hệ "một danh mục thuộc về một người dùng".
     */
    public function user()
    {
        // Giả sử bảng 'categories' của bạn có cột 'user_id'
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        if ($this->banner_image_path) {
            return asset('storage/' . $this->banner_image_path);
        }
        return null;
    }

    /**
     * Get gallery images URLs
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
     * Get gallery images count
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