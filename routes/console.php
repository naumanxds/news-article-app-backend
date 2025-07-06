<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:fetch-newsapiorg-article-command')->daily();
Schedule::command('app:fetch-nytimes-articles-command')->daily();
Schedule::command('app:fetch-theguardian-article-command')->daily();