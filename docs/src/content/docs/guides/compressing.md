---
title: Compressing Images
description: Learn how to compress images to specific file sizes while maintaining quality.
---

The `compressToJpg` method allows you to compress images to a target file size, automatically adjusting the quality to meet your requirements.

## Basic Usage

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$processor->compressToJpg(
    'path/to/input.jpg',    // Input image path
    'path/to/output.jpg',   // Output image path
    100                      // Target file size in KB
);
```

## How Compression Works

The method uses an iterative approach to reach your target file size:

1. Starts with the specified quality (default: 80)
2. Compresses the image
3. Checks the resulting file size
4. If too large, reduces quality by 10 and repeats
5. Stops when target size is reached or quality drops below 10

```php
// Compress to approximately 100KB
$processor->compressToJpg('large.jpg', 'compressed.jpg', 100);

// Compress to 50KB with custom starting quality
$processor->compressToJpg('large.jpg', 'compressed.jpg', 50, 90);
```

:::tip
The method will get as close as possible to your target size. The final file may be slightly smaller than your target, but never larger.
:::

## Common Compression Scenarios

### 1. Web Optimization

Optimize images for fast web loading:

```php
$processor = new ImageProcessor();

// Hero images - balance quality and size
$processor->compressToJpg('hero.jpg', 'hero-optimized.jpg', 200);

// Content images - smaller size
$processor->compressToJpg('content.jpg', 'content-optimized.jpg', 100);

// Thumbnails - aggressive compression
$processor->compressToJpg('thumb.jpg', 'thumb-optimized.jpg', 30);
```

### 2. Email Attachments

Reduce file sizes for email:

```php
// Compress to under 1MB for email
$processor->compressToJpg('photo.jpg', 'email-photo.jpg', 1000);

// Multiple attachments - keep each under 500KB
$photos = ['photo1.jpg', 'photo2.jpg', 'photo3.jpg'];

foreach ($photos as $index => $photo) {
    $processor->compressToJpg(
        $photo,
        "email-photo-{$index}.jpg",
        500
    );
}
```

### 3. Mobile App Assets

Optimize images for mobile apps:

```php
// App icons - small size, good quality
$processor->compressToJpg('icon.jpg', 'icon-compressed.jpg', 50);

// Splash screens - larger but still optimized
$processor->compressToJpg('splash.jpg', 'splash-compressed.jpg', 300);

// Background images - balance size and quality
$processor->compressToJpg('bg.jpg', 'bg-compressed.jpg', 150);
```

## Custom Quality Settings

Control the starting quality for better results:

```php
$processor = new ImageProcessor();

// Start with high quality (90) for important images
$processor->compressToJpg('portrait.jpg', 'portrait-compressed.jpg', 200, 90);

// Start with medium quality (80) - default
$processor->compressToJpg('photo.jpg', 'photo-compressed.jpg', 100, 80);

// Start with lower quality (70) for faster processing
$processor->compressToJpg('screenshot.jpg', 'screenshot-compressed.jpg', 50, 70);
```

## Batch Compression

Compress multiple images efficiently:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Configuration
$inputDir = 'originals/';
$outputDir = 'compressed/';
$targetSize = 100; // KB

// Create output directory
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Get all JPEG images
$images = glob($inputDir . '*.{jpg,jpeg}', GLOB_BRACE);

$totalOriginalSize = 0;
$totalCompressedSize = 0;

foreach ($images as $image) {
    $filename = basename($image);
    $outputPath = $outputDir . $filename;
    
    $originalSize = filesize($image);
    $totalOriginalSize += $originalSize;
    
    try {
        $processor->compressToJpg($image, $outputPath, $targetSize);
        
        $compressedSize = filesize($outputPath);
        $totalCompressedSize += $compressedSize;
        
        $savings = round((1 - $compressedSize / $originalSize) * 100, 2);
        
        echo "✅ {$filename}: " . 
             round($originalSize / 1024, 2) . "KB → " . 
             round($compressedSize / 1024, 2) . "KB " .
             "({$savings}% reduction)\n";
             
    } catch (Exception $e) {
        echo "❌ Error compressing {$filename}: " . $e->getMessage() . "\n";
    }
}

$totalSavings = round((1 - $totalCompressedSize / $totalOriginalSize) * 100, 2);
echo "\n📊 Total: " . 
     round($totalOriginalSize / 1024 / 1024, 2) . "MB → " . 
     round($totalCompressedSize / 1024 / 1024, 2) . "MB " .
     "({$totalSavings}% reduction)\n";
```

## Progressive Compression

Create multiple compression levels:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$inputImage = 'photo.jpg';
$compressionLevels = [
    'high' => 300,    // High quality
    'medium' => 150,  // Medium quality
    'low' => 75,      // Low quality
    'tiny' => 30      // Tiny size
];

foreach ($compressionLevels as $level => $size) {
    $outputPath = "compressed/{$level}.jpg";
    $processor->compressToJpg($inputImage, $outputPath, $size);
    
    $fileSize = round(filesize($outputPath) / 1024, 2);
    echo "Created {$level} version: {$fileSize}KB\n";
}
```

## Combining with Resizing

For best results, resize before compressing:

```php
$processor = new ImageProcessor();

// Step 1: Resize to web dimensions
$processor->resizeImage('huge.jpg', 'temp.jpg', 1200, 800);

// Step 2: Compress to target size
$processor->compressToJpg('temp.jpg', 'final.jpg', 150);

// Step 3: Clean up
unlink('temp.jpg');

// Or use the built-in combined method
$processor->resizeAndCompress('huge.jpg', 'final.jpg', 1200, 800, 150);
```

:::note
Resizing before compression gives you more control over the final quality and file size.
:::

## Quality vs. File Size

Understanding the trade-offs:

```php
$processor = new ImageProcessor();

// Same image, different target sizes
$targets = [
    'ultra' => 500,   // Excellent quality, larger file
    'high' => 200,    // Great quality, good size
    'medium' => 100,  // Good quality, small size
    'low' => 50,      // Acceptable quality, tiny size
    'minimal' => 20   // Poor quality, minimal size
];

foreach ($targets as $name => $size) {
    $processor->compressToJpg('original.jpg', "{$name}.jpg", $size);
}
```

| Target Size | Quality | Best For |
|-------------|---------|----------|
| 500KB+ | Excellent | Print, portfolios, high-res displays |
| 200-500KB | Great | Hero images, featured content |
| 100-200KB | Good | Standard web images |
| 50-100KB | Acceptable | Thumbnails, galleries |
| <50KB | Poor | Icons, tiny previews |

## Format Conversion

The method always outputs JPEG:

```php
// PNG to JPEG with compression
$processor->compressToJpg('graphic.png', 'graphic.jpg', 100);

// GIF to JPEG with compression
$processor->compressToJpg('animation.gif', 'static.jpg', 75);

// JPEG to JPEG with compression
$processor->compressToJpg('photo.jpg', 'compressed.jpg', 150);
```

:::caution
Converting PNG images with transparency to JPEG will result in a white background, as JPEG doesn't support transparency.
:::

## Error Handling

Handle compression errors gracefully:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    // Validate input
    $inputPath = 'photo.jpg';
    if (!file_exists($inputPath)) {
        throw new Exception("Input file not found");
    }
    
    // Check if it's an image
    if (getimagesize($inputPath) === false) {
        throw new Exception("Invalid image file");
    }
    
    // Compress
    $processor->compressToJpg($inputPath, 'output.jpg', 100);
    
    // Verify output
    if (!file_exists('output.jpg')) {
        throw new Exception("Compression failed");
    }
    
    echo "Compression successful!";
    
} catch (Exception $e) {
    error_log("Compression error: " . $e->getMessage());
    echo "Failed to compress image.";
}
```

## Performance Tips

### 1. Set Realistic Targets

```php
// Check original size first
$originalSize = filesize('photo.jpg') / 1024; // KB

// Don't set target larger than original
$targetSize = min(100, $originalSize * 0.8);

$processor->compressToJpg('photo.jpg', 'compressed.jpg', $targetSize);
```

### 2. Use Appropriate Starting Quality

```php
// For photos with lots of detail
$processor->compressToJpg('detailed.jpg', 'out.jpg', 100, 90);

// For simple graphics
$processor->compressToJpg('simple.jpg', 'out.jpg', 50, 70);
```

### 3. Monitor Processing Time

```php
$start = microtime(true);

$processor->compressToJpg('large.jpg', 'compressed.jpg', 100);

$duration = microtime(true) - $start;
echo "Compression took " . round($duration, 2) . " seconds\n";
```

## Method Signature

```php
public function compressToJpg(
    string $inputImagePath,   // Path to input image
    string $outputImagePath,  // Path to save compressed image
    int $targetFileSize = 100, // Target size in KB (default: 100)
    int $quality = 80         // Starting quality 0-100 (default: 80)
): void
```

## See Also

- [Resizing Images](/guides/resizing/) - Resize before compressing
- [Combined Operations](/guides/combined-operations/) - Resize and compress together
- [API Reference](/reference/compress-to-jpg/) - Complete method documentation
