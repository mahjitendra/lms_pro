<?php

namespace App\Jobs\Course;

use App\Models\Course\Course;
use App\Models\User;
use App\Models\Assessment\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

// In a real project, you would use a PDF generation library.
// use PDF; // e.g., from barryvdh/laravel-dompdf

class GenerateCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        Log::info("Starting GenerateCertificateJob for User ID: {$this->user->id} and Course ID: {$this->course->id}");

        // --- PDF Generation Logic ---
        // In a real app, you would load a Blade view with the certificate data
        // and use a library to convert it to a PDF.
        $certificateData = [
            'user_name' => $this->user->name,
            'course_name' => $this->course->title,
            'completion_date' => now()->format('F j, Y'),
        ];

        // $pdf = PDF::loadView('templates.certificate', $certificateData);
        // $pdfContent = $pdf->output();

        // We will simulate the PDF content for this example.
        $pdfContent = "PDF Certificate for {$this->user->name} for completing {$this->course->title}.";

        $fileName = 'certificate_' . $this->user->id . '_' . $this->course->id . '_' . time() . '.pdf';
        $filePath = 'certificates/' . $fileName;

        // Store the PDF in the private storage
        Storage::disk('local')->put($filePath, $pdfContent);
        Log::info("Generated and stored certificate PDF at: {$filePath}");

        // --- Create Certificate Record ---
        Certificate::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'file_path' => $filePath,
            'issued_at' => now(),
        ]);

        Log::info("GenerateCertificateJob completed successfully for User ID: {$this->user->id}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("GenerateCertificateJob failed for User ID: {$this->user->id} and Course ID: {$this->course->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}