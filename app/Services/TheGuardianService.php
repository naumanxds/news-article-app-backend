<?php

namespace App\Services;

use App\Interfaces\FetchArticleInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TheGuardianService implements FetchArticleInterface
{
    const API_BASE_URL = 'https://content.guardianapis.com/search';
    const DAILY_API_LIMIT = 500;
    const DAY_DIFFERENCE_FROM_TODAY = 2;
    const DELAY_SECONDS = 1;

    private string $apiKey = '';

    public function __construct()
    {
        $this->apiKey = env('THE_GUARDIAN_API_KEY', '');
    }

    /**
     * Gets the name of the platform.
     *
     * @return string
     */
    public function platformName(): string
    {
        return 'TheGuardian';
    }

    /**
     * Gets articles from the TheGuardian API based on the provided parameters.
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

            $data = json_decode($data, true) ?? [];
            if (isset($data['response']) && $data['response']['status'] === 'ok' && isset($data['response']['results'])) {
                return $data['response']['results'];
            }
        } catch (Exception $e) {
            Log::error('TheGuardianService :: fetchArticles :: Error fetching articles :: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Parses the data returned from the TheGuardian API into a structured format for database storage.
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
            if (empty($article['webTitle'])) {
                continue;
            }

            $parsedData[] = [
                'title' => $article['webTitle'],
                'author' => $article['fields']['byline'] ?? '',
                'content' => $article['fields']['bodyText'] ?? '',
                'url' => $article['webUrl'] ?? '',
                'image_url' => $article['fields']['thumbnail'],
                'source' => $article['fields']['publication'] ?? '',
                'data_source' => $this->platformName(),
                'published_at' => isset($article['webPublicationDate']) ? date('Y-m-d', strtotime($article['webPublicationDate'])) : null,
                'tag_id' => $tagId,
            ];
        }

        return $parsedData;
    }
}
