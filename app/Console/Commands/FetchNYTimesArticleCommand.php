<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFetchArticle;
use App\Models\Tag;
use App\Services\NYTimesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchNYTimesArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-nytimes-articles-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will push payload to Queue for articles to be fetched from NewYorkTimes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int)floor(NYTimesService::DAILY_API_LIMIT / Tag::count());
        $tags = Tag::all();
        if ($limit < 1) {
            $limit = 1;
            $tags = Tag::orderBy('last_fetched_at', 'asc')->take(NYTimesService::DAILY_API_LIMIT)->get();

            $warningMessage = 'FetchNYTimesArticleCommand :: handle :: The limit is reached and some of the records will not be fetched.';
            $this->warn($warningMessage);
            Log::warning($warningMessage);
        }

        foreach ($tags as $tag) {
            for ($i = 0; $i < $limit; $i++) {
                $params = [
                    'q' => $tag->name,
                    'page' => $i + 1,
                    'sort' => 'best',
                    'end_date' => today()->subDay(NYTimesService::DAY_DIFFERENCE_FROM_TODAY)->format('Ymd'),
                    'begin_date' => today()->subDays(NYTimesService::DAY_DIFFERENCE_FROM_TODAY + 15)->format('Ymd'),
                ];

                dispatch(new ProcessFetchArticle(
                    new NYTimesService(),
                    $params,
                    $tag->id,
                    NYTimesService::DELAY_SECONDS
                ));
            }

            $tag->update(['last_fetched_at' => now()]);
        }
    }
}
