<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * ModuleName: Image Compression Service
 * Purpose: Compress and convert images to WebP format while maintaining quality
 * 
 * @category Services
 * @package  App\Services
 * @created  2025-11-24
 */
class ImageCompressionService
{
    /**
     * WebP quality setting (0-100)
     * 85 provides excellent quality with good compression
     */
    const WEBP_QUALITY = 85;

    /**
     * Image manager instance
     */
    protected $manager;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialize ImageManager with GD driver
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Compress and convert image to WebP format
     * 
     * @param UploadedFile $file The uploaded image file
     * @param string $directory The storage directory (e.g., 'site-settings')
     * @param string|null $disk The storage disk (default: 'public')
     * @return string The path to the stored WebP image
     */
    public function compressAndStore(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        // Generate unique filename with .webp extension
        $filename = uniqid() . '_' . time() . '.webp';
        $path = $directory . '/' . $filename;

        // Read and process the uploaded image
        $image = $this->manager->read($file->getRealPath());

        // Encode to WebP format with quality setting (no resizing)
        $encoded = $image->toWebp(self::WEBP_QUALITY);

        // Store the WebP image
        Storage::disk($disk)->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Compress and convert image from path to WebP format
     * Useful for re-compressing existing images
     * 
     * @param string $existingPath The existing image path in storage
     * @param string|null $disk The storage disk (default: 'public')
     * @return string The path to the stored WebP image
     */
    public function recompressToWebP(string $existingPath, string $disk = 'public'): string
    {
        // Get the full path from storage
        $fullPath = Storage::disk($disk)->path($existingPath);

        // Read the existing image
        $image = $this->manager->read($fullPath);

        // Generate new filename with .webp extension
        $pathInfo = pathinfo($existingPath);
        $filename = $pathInfo['filename'] . '_' . time() . '.webp';
        $newPath = $pathInfo['dirname'] . '/' . $filename;

        // Encode to WebP format
        $encoded = $image->toWebp(self::WEBP_QUALITY);

        // Store the WebP image
        Storage::disk($disk)->put($newPath, (string) $encoded);

        // Delete the old image
        Storage::disk($disk)->delete($existingPath);

        return $newPath;
    }

    /**
     * Check if image is already in WebP format
     * 
     * @param string $path The image path
     * @return bool
     */
    public function isWebP(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return $extension === 'webp';
    }

    /**
     * Get supported image MIME types
     * 
     * @return array
     */
    public static function getSupportedMimeTypes(): array
    {
        return [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/webp',
            'image/svg+xml',
        ];
    }

    /**
     * Validate if file is a supported image
     * 
     * @param UploadedFile $file
     * @return bool
     */
    public function isSupportedImage(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), self::getSupportedMimeTypes());
    }
}
