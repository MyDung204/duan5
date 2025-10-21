<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; // Quan trọng: Thêm dòng này

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'author_name',
        'short_description',
        'content',
        'image_path',
    ];

    /**
     * Tự động tạo slug từ title mỗi khi lưu model.
     */
    protected static function booted(): void
    {
        static::saving(function ($category) {
            $category->slug = Str::slug($category->title);
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}