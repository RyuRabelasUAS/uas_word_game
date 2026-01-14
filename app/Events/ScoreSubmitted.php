<?php

namespace App\Events;

use App\Models\Score;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $score;

    /**
     * Create a new event instance.
     */
    public function __construct(Score $score)
    {
        $this->score = $score->load(['user', 'level']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('leaderboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'score.submitted';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->score->id,
            'user' => [
                'id' => $this->score->user->id,
                'name' => $this->score->user->name,
            ],
            'level' => [
                'id' => $this->score->level->id,
                'title' => $this->score->level->title,
            ],
            'score' => $this->score->score,
            'time_seconds' => $this->score->time_seconds,
            'game_type' => $this->score->game_type,
            'details' => $this->score->details,
            'created_at' => $this->score->created_at->toISOString(),
        ];
    }
}
