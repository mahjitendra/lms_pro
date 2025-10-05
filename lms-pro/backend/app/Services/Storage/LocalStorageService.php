<?php

namespace App\Services\Storage;

use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Service for handling file operations on the local disk.
 */
class LocalStorageService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = 'local'; // Using the 'local' disk defined in config/filesystems.php
    }

    /**
     * Store an uploaded file.
     *
     * @param UploadedFile $file
     * @param string $directory The subdirectory to store the file in.
     * @return string The path to the stored file.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $fileName = FileHelper::generateUniqueFileName($file);
        return $file->storeAs($directory, $fileName, $this->disk);
    }

    /**
     * Get the full URL for a publicly accessible file.
     * This requires using the 'public' disk.
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Delete a file from the disk.
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