<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'featured_image',
        'author_id', 'category', 'tags', 'status', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scope for published posts
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                    ->where('published_at', '<=', now());
    }

    // Scope for filtering by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Get excerpt or truncated content
    public function getExcerptAttribute($value)
    {
        return $value ?: \Str::limit(strip_tags($this->content), 150);
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PUBLISHED => 'success',
            self::STATUS_DRAFT => 'warning',
            self::STATUS_ARCHIVED => 'secondary',
            default => 'secondary'
        };
    }
}
