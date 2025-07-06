<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ArticleFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    protected $drop_id = false;

    public function tagId(int $id)
    {
        return $this->where('tag_id', '=', $id);
    }

    public function dataSource(array $dataSource)
    {
        return $this->whereIn('data_source', $dataSource);
    }

    public function publishedAt(array $dates = [])
    {
        if (isset($dates['from']) && isset($dates['to'])) {
            return $this->whereBetween('published_at', [$dates['from'], $dates['to']]);
        } elseif (isset($dates['from'])) {
            return $this->where('published_at', '>=', $dates['from']);
        } elseif (isset($dates['to'])) {
            return $this->where('published_at', '<=', $dates['to']);
        }
    }
}
