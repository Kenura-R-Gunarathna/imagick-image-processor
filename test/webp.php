<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Quality setting
$quality = 85;

// File name
$fileName = 'webp-test.jpg';

// Read file path
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path
$outputPath = __DIR__ . '/images/output/' . str_replace('.jpg', '.webp', $fileName);

// Convert to WebP
$processor->convertToWebP($filePath, $outputPath, $quality);

// Display results
echo "WebP Conversion Test\n";
echo "====================\n";
echo "Input: {$filePath}\n";
echo "Output: {$outputPath}\n";
echo "Quality: {$quality}\n";

if (file_exists($outputPath)) {
    $originalSize = filesize($filePath);
    $webpSize = filesize($outputPath);
    $savings = round((1 - $webpSize / $originalSize) * 100, 2);
    
    echo "\nResults:\n";
    echo "Original size: " . round($originalSize / 1024, 2) . " KB\n";
    echo "WebP size: " . round($webpSize / 1024, 2) . " KB\n";
    echo "Savings: {$savings}%\n";
    echo "\n✅ WebP conversion successful!\n";
} else {
    echo "\n❌ WebP conversion failed!\n";
}
