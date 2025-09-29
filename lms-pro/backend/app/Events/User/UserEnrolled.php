<?php

namespace App\Events\User;

use App\Models\Course\Enrollment;
use App\Models\User;
use App\Models\Course\Course;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEnrolled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The user who enrolled.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The course the user enrolled in.
     *
     * @var \App\Models\Course\Course
     */
    public $course;

    /**
     * The enrollment record.
     *
     * @var \App\Models\Course\Enrollment
     */
    public $enrollment;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Course\Course $course
     * @param \App\Models\Course\Enrollment $enrollment
     */
    public function __construct(User $user, Course $course, Enrollment $enrollment)
    {
        $this->user = $user;
        $this->course = $course;
        $this->enrollment = $enrollment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Could be used to update the UI for the instructor or an admin dashboard
        // return [new PrivateChannel('course.' . $this->course->id)];
        return [];
    }
}