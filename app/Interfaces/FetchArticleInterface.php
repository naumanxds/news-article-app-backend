<?php

namespace App\Interfaces;

interface FetchArticleInterface
{
    public function platformName(): string;

    public function fetchArticles(array $params): array;

    public function parseData(array $data, int $tagId): array;
}
