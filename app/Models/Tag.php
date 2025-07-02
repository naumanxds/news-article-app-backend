<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'tags_id', 'id');
    }
}
