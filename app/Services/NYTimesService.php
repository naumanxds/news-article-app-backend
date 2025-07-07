<?php

namespace App\Services;

use App\Interfaces\FetchArticleInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NYTimesService implements FetchArticleInterface
{
    const API_BASE_URL = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
    const DAILY_API_LIMIT = 100;
    const DAY_DIFFERENCE_FROM_TODAY = 1;
    const DELAY_SECONDS = 1;

    private string $apiKey = '';

    public function __construct()
    {
        $this->apiKey = env('NEWYORK_TIMES_API_KEY', '');
    }

    /**
     * Gets the name of the platform.
     *
     * @return string
     */
    public function platformName(): string
    {
        return 'NewYorkTimes';
    }

    /**
     * Gets articles from the NYTimes API based on the provided parameters.
     *
     * @param array $params
     *
     * @return array
     */
    public function fetchArticles(array $params): array
    {
        try {
            $data = Http::get(self::API_BASE_URL,
                array_merge($params, ['api-key' => $this->apiKey]),
            );

            $data = json_decode($data->body(), true) ?? [];
            if (isset($data['response']['docs'])) {
                return $data['response']['docs'];
            }
        } catch (Exception $e) {
            Log::error('NewsApiOrgService :: fetchArticles :: Error fetching articles :: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Parses the data returned from the NYTimes API into a structured format for database storage.
     *
     * @param array $data
     * @param int $tagId
     *
     * @return array
     */
    public function parseData(array $data, int $tagId): array
    {
        if (empty($data)) {
            return [];
        }

        $parsedData = [];
        foreach ($data as $article) {
            if (empty($article['headline']['main'])) {
                continue;
            }

            $parsedData[] = [
                'title' => $article['headline']['main'],
                'author' => $article['byline']['original'] ?? '',
                'content' => $article['snippet'] ?? '',
                'url' => $article['web_url'] ?? '',
                'image_url' => isset($article['multimedia'])
                    ? (isset($article['multimedia']['default']) ? $article['multimedia']['default']['url'] : '')
                    : '',
                'source' => $article['source'] ?? '',
                'data_source' => $this->platformName(),
                'published_at' => isset($article['pub_date']) ? date('Y-m-d', strtotime($article['pub_date'])) : null,
                'tag_id' => $tagId,
            ];
        }

        return $parsedData;
    }
}
