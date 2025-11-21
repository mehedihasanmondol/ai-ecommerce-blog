<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

class ImageService
{
    /**
     * Get the maximum upload size from PHP configuration in bytes
     */
    public static function getMaxUploadSize(): int
    {
        $uploadMax = self::parseSize(ini_get('upload_max_filesize'));
        $postMax = self::parseSize(ini_get('post_max_size'));
        $memoryLimit = self::parseSize(ini_get('memory_limit'));

        // Return the smallest of the three
        return min($uploadMax, $postMax, $memoryLimit);
    }

    /**
     * Get the maximum upload size in human-readable format
     */
    public static function getMaxUploadSizeFormatted(): string
    {
        return self::formatBytes(self::getMaxUploadSize());
    }

    /**
     * Parse size string from php.ini to bytes
     */
    private static function parseSize(string $size): int
    {
        $size = trim($size);
        $unit = strtolower($size[strlen($size) - 1]);
        $value = (int) $size;

        switch ($unit) {
            case 'g':
                $value *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $value *= 1024 * 1024;
                break;
            case 'k':
                $value *= 1024;
                break;
        }

        return $value;
    }

    /**
     * Format bytes to human-readable size
     */
    private static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Validate file size against PHP ini limits
     */
    public static function validateFileSize(UploadedFile $file): bool
    {
        $maxSize = self::getMaxUploadSize();
        return $file->getSize() <= $maxSize;
    }

    /**
     * Process and convert image to WebP format with compression
     * 
     * @param UploadedFile $file
     * @param string $folder Folder name in storage/app/public
     * @param int $quality Quality percentage (1-100), default 85
     * @return string Path to the stored image
     * @throws Exception
     */
    public static function processAndStore(UploadedFile $file, string $folder = 'products', int $quality = 85): string
    {
        // Validate file size
        if (!self::validateFileSize($file)) {
            throw new Exception('File size exceeds the maximum allowed size of ' . self::getMaxUploadSizeFormatted());
        }

        // Validate it's an image
        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'])) {
            throw new Exception('File must be an image (JPEG, PNG, GIF, WebP, or BMP)');
        }

        try {
            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.webp';
            $path = $folder . '/' . $filename;
            $fullPath = storage_path('app/public/' . $path);

            // Create directory if it doesn't exist
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Load and process image using Intervention Image v3
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            
            // Encode to WebP with compression
            // Quality: 85 is a good balance between size and quality
            // For more aggressive compression, use 75-80
            $encoded = $image->toWebp($quality);

            // Save the image
            $encoded->save($fullPath);

            return $path;
        } catch (Exception $e) {
            throw new Exception('Failed to process image: ' . $e->getMessage());
        }
    }

    /**
     * Process and convert image to WebP with aggressive compression
     * Uses lower quality for maximum size reduction
     * 
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public static function processAndStoreCompressed(UploadedFile $file, string $folder = 'products'): string
    {
        return self::processAndStore($file, $folder, 75); // More aggressive compression
    }

    /**
     * Process and convert image to WebP with high quality
     * Uses higher quality for better image appearance
     * 
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public static function processAndStoreHighQuality(UploadedFile $file, string $folder = 'products'): string
    {
        return self::processAndStore($file, $folder, 90); // Higher quality
    }

    /**
     * Delete image file from storage
     */
    public static function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get image dimensions without loading entire image
     */
    public static function getImageDimensions(string $path): ?array
    {
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            return null;
        }

        $size = getimagesize($fullPath);
        
        if ($size === false) {
            return null;
        }

        return [
            'width' => $size[0],
            'height' => $size[1],
            'mime' => $size['mime'] ?? null,
        ];
    }

    /**
     * Create thumbnail from WebP image
     * 
     * @param string $sourcePath Path to source image in storage
     * @param int $width Thumbnail width
     * @param int $height Thumbnail height
     * @param string $folder Folder to store thumbnail
     * @return string Path to thumbnail
     */
    public static function createThumbnail(string $sourcePath, int $width = 300, int $height = 300, string $folder = 'products/thumbnails'): string
    {
        $fullSourcePath = storage_path('app/public/' . $sourcePath);
        
        if (!file_exists($fullSourcePath)) {
            throw new Exception('Source image not found');
        }

        // Generate thumbnail filename
        $filename = 'thumb_' . uniqid() . '_' . time() . '.webp';
        $path = $folder . '/' . $filename;
        $fullPath = storage_path('app/public/' . $path);

        // Create directory if it doesn't exist
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Create thumbnail using Intervention Image v3
        $manager = new ImageManager(new Driver());
        $image = $manager->read($fullSourcePath);
        
        // Cover fit to dimensions (crop to fit)
        $image->cover($width, $height);
        
        // Encode to WebP and save
        $encoded = $image->toWebp(85);
        $encoded->save($fullPath);

        return $path;
    }

    /**
     * Batch process multiple images
     * 
     * @param array $files Array of UploadedFile objects
     * @param string $folder
     * @param int $quality
     * @return array Array of paths
     */
    public static function processBatch(array $files, string $folder = 'products', int $quality = 85): array
    {
        $paths = [];
        $errors = [];

        foreach ($files as $index => $file) {
            try {
                $paths[] = self::processAndStore($file, $folder, $quality);
            } catch (Exception $e) {
                $errors[$index] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            throw new Exception('Some images failed to process: ' . json_encode($errors));
        }

        return $paths;
    }
}
