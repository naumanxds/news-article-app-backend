<?php

namespace App\Traits;

trait ArticleValidationRule
{
    public function listArticleValidationRules(): array
    {
        return [
            'filters.tag_id' => 'exists:tags,id',
            'filters.data_source.*' => 'in:NewsApiOrg,NewYorkTimes,TheGuardian',
            'filters.published_at.from' => 'date_format:Y-m-d',
            'filters.published_at.to' => 'date_format:Y-m-d|after_or_equal:filters.published_at.from',
        ];
    }
}
