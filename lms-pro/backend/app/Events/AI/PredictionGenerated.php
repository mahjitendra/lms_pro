<?php

namespace App\Events\AI;

use App\Models\AI\Prediction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PredictionGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The prediction that was generated.
     *
     * @var \App\Models\AI\Prediction
     */
    public $prediction;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\AI\Prediction $prediction
     */
    public function __construct(Prediction $prediction)
    {
        $this->prediction = $prediction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast the prediction to the user who made the request
        // return [new PrivateChannel('user.' . $this->prediction->user_id)];
        return [];
    }
}