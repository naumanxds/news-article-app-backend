<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFetchArticle;
use App\Models\Tag;
use App\Services\TheGuardianService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchTheGuardianArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-theguardian-article-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int)floor(TheGuardianService::DAILY_API_LIMIT / Tag::count());
        $tags = Tag::all();
        if ($limit < 1) {
            $limit = 1;
            $tags = Tag::orderBy('last_fetched_at', 'asc')->take(TheGuardianService::DAILY_API_LIMIT)->get();

            $warningMessage = 'FetchTheGuardianArticleCommand :: handle :: The limit is reached and some of the records will not be fetched.';
            $this->warn($warningMessage);
            Log::warning($warningMessage);
        }

        foreach ($tags as $tag) {
            for ($i = 0; $i < $limit; $i++) {
                $params = [
                    'q' => $tag->name,
                    'page' => $i + 1,
                    'order-by' => 'newest',
                    'show-fields' => 'all',
                    'page-size' => 50,
                    'to-date' => today()->subDay(TheGuardianService::DAY_DIFFERENCE_FROM_TODAY)->format('Y-m-d'),
                    'from-date' => today()->subDays(TheGuardianService::DAY_DIFFERENCE_FROM_TODAY + 1)->format('Y-m-d'),
                ];

                dispatch(new ProcessFetchArticle(
                    new TheGuardianService(),
                    $params,
                    $tag->id,
                    TheGuardianService::DELAY_SECONDS
                ));
            }

            $tag->update(['last_fetched_at' => now()]);
        }
    }
}
