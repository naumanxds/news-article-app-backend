<?php

namespace App\Jobs\Middleware;

use Closure;

class DelayAfterJobMiddleware
{
    public function __construct(
        private int $delay = 0,
    ) { }

    public function handle($job, Closure $next)
    {
        sleep($this->delay);

        $next($job);
    }
}
