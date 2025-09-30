<?php

namespace App\Jobs\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $mailable;
    public $recipient;

    /**
     * Create a new job instance.
     *
     * @param string $recipient The email address of the recipient.
     * @param \Illuminate\Mail\Mailable $mailable The Mailable class instance to be sent.
     */
    public function __construct(string $recipient, Mailable $mailable)
    {
        $this->recipient = $recipient;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mailableClass = get_class($this->mailable);
        Log::info("Starting SendEmailJob for {$mailableClass} to {$this->recipient}");

        try {
            Mail::to($this->recipient)->send($this->mailable);
            Log::info("Successfully sent email via SendEmailJob to {$this->recipient}");
        } catch (\Exception $e) {
            // Re-throw the exception to let Laravel's queue handle the failure and retry logic
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $mailableClass = get_class($this->mailable);
        Log::error("SendEmailJob failed to send {$mailableClass} to {$this->recipient}", [
            'error' => $exception->getMessage(),
        ]);
    }
}