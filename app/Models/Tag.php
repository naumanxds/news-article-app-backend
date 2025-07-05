<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    protected $fillable = ['name', 'last_fetched_at'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'tags_id', 'id');
    }
}
