<?php

namespace App\Events\AI;

use App\Models\Content\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The video that has been processed.
     *
     * @var \App\Models\Content\Video
     */
    public $video;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Content\Video $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // This could broadcast a notification to the instructor who uploaded the video
        // For example: return [new PrivateChannel('user.' . $this->video->uploader_id)];
        return [];
    }
}