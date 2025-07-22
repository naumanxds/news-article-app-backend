<?php

namespace App\Jobs;

use App\Events\ArticleCreated;
use App\Jobs\Middleware\DelayAfterJobMiddleware;
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
        protected $addDelay
    ) { }

    public function middleware(): array
    {
        return [
            new DelayAfterJobMiddleware($this->addDelay),
        ];
    }

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
            Log::warning(
                'ProcessFetchArticle :: handle :: Data not found for the provided params. :: ' . $this->fetchArticleService->platformName() . ' :: ',
                $this->params
            );

            return;
        }

        $parsedData = $this->fetchArticleService->parseData($data, $this->tagId);
        foreach ($parsedData as $article) {
            Article::create($article);
        }

        broadcast(new ArticleCreated(
            $this->fetchArticleService->platformName(),
        ))->toOthers();
    }
}
