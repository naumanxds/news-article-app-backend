<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFetchArticle;
use App\Models\Tag;
use App\Services\NewsApiOrgService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchNewsApiOrgArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-newsapiorg-article-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will push payload to Queue for articles to be fetched from NewsApiOrg';

    public function __construct(
        private NewsApiOrgService $newsApiOrgService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limitForAll = (int)floor(NewsApiOrgService::DAILY_API_LIMIT / Tag::count());
        $tags = Tag::all();
        if ($limitForAll < 1) {
            $limitForAll = 1;
            $tags = Tag::orderBy('last_fetched_at', 'asc')->take(NewsApiOrgService::DAILY_API_LIMIT)->get();

            $warningMessage = 'FetchNewsApiOrgArticleCommand :: handle :: The limit is reached and some of the records will not be fetched.';
            $this->warn($warningMessage);
            Log::warning($warningMessage);
        }

        foreach ($tags as $tag) {
            $limit = $limitForAll;
            for ($i = 0; $i < $limit; $i++) {
                $params = [
                    'q' => $tag->name,
                    'pageSize' => NewsApiOrgService::PAGE_SIZE,
                    'page' => $i + 1,
                    'sortBy' => 'popularity',
                    'to' => today()->subDay(NewsApiOrgService::DAY_DIFFERENCE_FROM_TODAY)->format('Y-m-d'),
                    'from' => today()->subDays(NewsApiOrgService::DAY_DIFFERENCE_FROM_TODAY + 5)->format('Y-m-d'),
                ];

                if ($i == 0) {
                    $totalPages = $this->newsApiOrgService->getPageCount($params);
                    if ($totalPages == 0) {
                        $this->warn("No articles found for tag: {$tag->name}");
                        Log::warning("FetchNewsApiOrgArticleCommand :: handle :: No articles found for tag: {$tag->name}");

                        break;
                    } else if ($limit > $totalPages) {
                        $limit = $totalPages;
                    }

                    $limit--;
                }

                dispatch(new ProcessFetchArticle(
                    new NewsApiOrgService(),
                    $params,
                    $tag->id,
                    NewsApiOrgService::DELAY_SECONDS,
                ));
            }

            $tag->update(['last_fetched_at' => now()]);
        }
    }
}
