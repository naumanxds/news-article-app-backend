<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticleCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $dataSource;

    public function __construct(string $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function broadcastOn(): array
    {
        return [new Channel('articles')];
    }

    public function broadcastAs(): string
    {
        return 'article.created';
    }

    public function broadcastWith(): array
    {
        $message = 'New Articles are Added from DataSource : ' . $this->dataSource;
        return [
            'message' => $message
        ];
    }
}
