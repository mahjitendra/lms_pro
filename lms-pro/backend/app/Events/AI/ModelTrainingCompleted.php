<?php

namespace App\Events\AI;

use App\Models\AI\AIModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelTrainingCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The AI model that has completed training.
     *
     * @var \App\Models\AI\AIModel
     */
    public $model;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\AI\AIModel $model
     */
    public function __construct(AIModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // This could broadcast to a private channel for the admin or instructor who started the training
        // For example: return [new PrivateChannel('user.' . $this->model->creator_id)];
        return [];
    }
}