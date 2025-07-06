<?php

namespace App\Models;

use App\ModelFilters\ArticleFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use Filterable;

    protected $fillable = ['title', 'author', 'content', 'url', 'image_url', 'source', 'data_source', 'published_at', 'tag_id'];

    protected $casts = [
        'published_at' => 'date:Y-m-d',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

    public function modelFilter()
    {
        return $this->provideFilter(ArticleFilter::class);
    }
}
