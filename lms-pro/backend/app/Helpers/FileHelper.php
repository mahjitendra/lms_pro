<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Helper class for file management and utility functions.
 */
class FileHelper
{
    /**
     * Generate a unique, sanitized filename for an uploaded file.
     *
     * @param UploadedFile $file
     * @return string
     */
    public static function generateUniqueFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = Str::slug($originalName);

        // Combine a timestamp, the sanitized name, and a random string to ensure uniqueness
        return time() . '_' . $sanitizedName . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Format file size in a human-readable format.
     *
     * @param int $bytes The file size in bytes.
     * @param int $precision The number of decimal places.
     * @return string
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get the MIME type of a file.
     *
     * @param string $filePath The path to the file.
     * @return string|false
     */
    public static function getMimeType(string $filePath): string|false
    {
        if (!file_exists($filePath)) {
            return false;
        }

        return mime_content_type($filePath);
    }
}