<?php

/**
 * Currency Symbol Update Script
 * 
 * This script updates all Blade view files to use dynamic currency
 * instead of hard-coded $ symbols
 */

$viewsPath = __DIR__ . '/resources/views';

// Patterns to replace
$patterns = [
    // Pattern 1: ${{ number_format($var, 2) }}
    '/\$\{\{\s*number_format\(([^,]+),\s*2\)\s*\}\}/i' => '{{ currency_format($1) }}',
    
    // Pattern 2: ${{ $var }}
    '/\$\{\{\s*\$([a-zA-Z_][a-zA-Z0-9_]*)\s*\}\}/i' => '{{ currency_format($$1) }}',
    
    // Pattern 3: $0, $∞ (literals)
    '/\$0/i' => '{{ currency_symbol() }}0',
    '/\$∞/i' => '{{ currency_symbol() }}∞',
];

function updateFile($filePath, $patterns) {
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    foreach ($patterns as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        return true;
    }
    
    return false;
}

function scanDirectory($directory, $patterns, &$updatedFiles = []) {
    $items = scandir($directory);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $path = $directory . DIRECTORY_SEPARATOR . $item;
        
        if (is_dir($path)) {
            scanDirectory($path, $patterns, $updatedFiles);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            if (updateFile($path, $patterns)) {
                $updatedFiles[] = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $path);
            }
        }
    }
}

echo "Starting currency symbol update...\n\n";

$updatedFiles = [];
scanDirectory($viewsPath, $patterns, $updatedFiles);

echo "Update complete!\n";
echo "Updated " . count($updatedFiles) . " files:\n\n";

foreach ($updatedFiles as $file) {
    echo "- " . $file . "\n";
}

echo "\n✓ All view files updated to use dynamic currency!\n";
