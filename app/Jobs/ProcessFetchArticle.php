<?php

namespace App\Jobs;

use App\Interfaces\FetchArticleInterface;
use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessFetchArticle implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected FetchArticleInterface $fetchArticleService,
        protected array $params,
        protected int $tagId,
    ) { }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (empty($this->params)) {
            Log::error('ProcessFetchArticle :: handle :: Params are empty.');

            return;
        }

        $data = $this->fetchArticleService->fetchArticles($this->params);
        if (empty($data)) {
            Log::warning('ProcessFetchArticle :: handle :: Data not found for the provided params. :: ', $this->params);

            return;
        }

        $parsedData = $this->fetchArticleService->parseData($data, $this->tagId);
        foreach ($parsedData as $article) {
            Article::create($article);
        }
    }
}
