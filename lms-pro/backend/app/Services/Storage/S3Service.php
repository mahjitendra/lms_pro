<?php

namespace App\Services\Storage;

use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * Service for handling file operations on Amazon S3.
 * This class is a placeholder to demonstrate a cloud storage strategy.
 */
class S3Service
{
    protected $disk;

    public function __construct()
    {
        $this->disk = 's3'; // Using the 's3' disk defined in config/filesystems.php
    }

    /**
     * Store an uploaded file on S3.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string The path to the stored file on S3.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $fileName = FileHelper::generateUniqueFileName($file);

        // The storeAs method on S3 is stream-based, making it memory efficient.
        return $file->storeAs($directory, $fileName, $this->disk);
    }

    /**
     * Get the full, publicly accessible URL for a file on S3.
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Get a temporary, secure URL for a private file on S3.
     *
     * @param string $path
     * @param int $minutes The number of minutes the URL should be valid for.
     * @return string
     */
    public function getTemporaryUrl(string $path, int $minutes = 15): string
    {
        return Storage::disk($this->disk)->temporaryUrl(
            $path,
            now()->addMinutes($minutes)
        );
    }

    /**
     * Delete a file from S3.
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }
        return false;
    }
}