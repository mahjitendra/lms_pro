<?php

namespace App\Jobs\Course;

use App\Mail\UserEnrolledMail; // Assuming this Mailable exists
use App\Models\Course\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEnrollmentEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $user;
    public $course;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Course\Course $course
     */
    public function __construct(User $user, Course $course)
    {
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting SendEnrollmentEmailJob for User ID: {$this->user->id} and Course ID: {$this->course->id}");

        // Create a new Mailable instance
        $email = new UserEnrolledMail($this->user, $this->course);

        // Send the email
        Mail::to($this->user->email)->send($email);

        Log::info("Successfully sent enrollment email to User ID: {$this->user->id}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("SendEnrollmentEmailJob failed for User ID: {$this->user->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}