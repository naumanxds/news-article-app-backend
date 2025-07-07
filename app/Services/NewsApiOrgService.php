<?php

namespace App\Services;

use App\Interfaces\FetchArticleInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsApiOrgService implements FetchArticleInterface
{
    const API_BASE_URL = 'https://newsapi.org/v2/everything';
    const DAILY_API_LIMIT = 100;
    const PAGE_SIZE = 100;
    const DAY_DIFFERENCE_FROM_TODAY = 1;
    const DELAY_SECONDS = 1;

    private string $apiKey = '';

    public function __construct()
    {
        $this->apiKey = env('NEWS_API_ORG_API_KEY', '');
    }

    /**
     * Gets the name of the platform.
     *
     * @return string
     */
    public function platformName(): string
    {
        return 'NewsApiOrg';
    }

    /**
     * Gets articles from the NewsApiOrg API based on the provided parameters.
     *
     * @param array $params
     *
     * @return array
     */
    public function fetchArticles(array $params): array
    {
        try {
            $data = Http::get(self::API_BASE_URL,
                array_merge($params, ['apiKey' => $this->apiKey]),
            );

            return json_decode($data->body(), true) ?? [];
        } catch (Exception $e) {
            Log::error('NewsApiOrgService :: fetchArticles :: Error fetching articles :: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Parses the data returned from the NewsApiOrg API into a structured format for database storage.
     *
     * @param array $data
     * @param int $tagId
     *
     * @return array
     */
    public function parseData(array $data, int $tagId): array
    {
        if (empty($data) || !isset($data['articles']) || !is_array($data['articles'])) {
            return [];
        }

        $parsedData = [];
        foreach ($data['articles'] as $article) {
            if (empty($article['title'])) {
                continue;
            }

            $parsedData[] = [
                'title' => $article['title'] ?? null,
                'author' => $article['author'] ?? null,
                'content' => $article['content'] ?? null,
                'url' => $article['url'] ?? null,
                'image_url' => $article['urlToImage'] ?? null,
                'source' => $article['source']['name'] ?? null,
                'data_source' => $this->platformName(),
                'published_at' => isset($article['publishedAt'])
                    ? date('Y-m-d', strtotime($article['publishedAt']))
                    : null,
                'tag_id' => $tagId,
            ];
        }

        return $parsedData;
    }

    /**
     * Calculates the total number of pages based on the provided parameters.
     */
    public function getPageCount(array $params = []): int
    {
        $res =$this->fetchArticles($params);

        return $res['totalResults']
            ? floor($res['totalResults'] / self::PAGE_SIZE)
            : 0;
    }
}
