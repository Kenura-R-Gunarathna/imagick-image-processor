---
title: WebP Conversion
description: Learn how to convert images to modern WebP format for superior web performance.
---

The `convertToWebP()` method converts images to the modern WebP format, which provides superior compression and quality compared to traditional formats like JPEG and PNG.

## Why WebP?

WebP is a modern image format developed by Google that offers:

- **Better Compression**: 25-35% smaller file sizes than JPEG
- **Transparency Support**: Like PNG, but with better compression
- **Wide Browser Support**: Supported by all modern browsers
- **Quality**: Better quality at smaller file sizes

## Basic Usage

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Convert to WebP with default quality (80)
$processor->convertToWebP('photo.jpg', 'photo.webp');

// Convert with custom quality
$processor->convertToWebP('photo.jpg', 'photo.webp', 90);
```

## Quality Settings

The quality parameter (0-100) controls the output quality:

```php
$processor = new ImageProcessor();

// High quality (larger file)
$processor->convertToWebP('photo.jpg', 'high-quality.webp', 95);

// Balanced quality (recommended)
$processor->convertToWebP('photo.jpg', 'balanced.webp', 80);

// Smaller file size
$processor->convertToWebP('photo.jpg', 'compressed.webp', 60);
```

### Quality Recommendations

| Quality | File Size | Use Case |
|---------|-----------|----------|
| 90-100 | Larger | High-quality photos, portfolios |
| 75-90 | Medium | Standard web images (recommended) |
| 60-75 | Small | Thumbnails, backgrounds |
| <60 | Very small | Icons, previews |

## Converting Different Formats

WebP supports conversion from all common formats:

### JPEG to WebP

```php
// Convert JPEG photo
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);

// Typical savings: 25-35% smaller file size
```

### PNG to WebP

```php
// Convert PNG with transparency
$processor->convertToWebP('logo.png', 'logo.webp', 90);

// Transparency is preserved!
// Typical savings: 50-70% smaller file size
```

### GIF to WebP

```php
// Convert GIF (first frame)
$processor->convertToWebP('image.gif', 'image.webp', 80);

// Note: Animations are not preserved
```

## Batch Conversion

Convert multiple images to WebP:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Configuration
$inputDir = 'images/';
$outputDir = 'webp/';
$quality = 85;

// Create output directory
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Get all images
$images = glob($inputDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

$totalOriginalSize = 0;
$totalWebPSize = 0;

foreach ($images as $image) {
    $filename = pathinfo($image, PATHINFO_FILENAME);
    $outputPath = $outputDir . $filename . '.webp';
    
    $originalSize = filesize($image);
    $totalOriginalSize += $originalSize;
    
    try {
        $processor->convertToWebP($image, $outputPath, $quality);
        
        $webpSize = filesize($outputPath);
        $totalWebPSize += $webpSize;
        
        $savings = round((1 - $webpSize / $originalSize) * 100, 2);
        
        echo "✅ {$filename}: " . 
             round($originalSize / 1024, 2) . "KB → " . 
             round($webpSize / 1024, 2) . "KB " .
             "({$savings}% smaller)\n";
             
    } catch (Exception $e) {
        echo "❌ Error: {$filename} - " . $e->getMessage() . "\n";
    }
}

$totalSavings = round((1 - $totalWebPSize / $totalOriginalSize) * 100, 2);
echo "\n📊 Total savings: {$totalSavings}%\n";
echo "Original: " . round($totalOriginalSize / 1024 / 1024, 2) . "MB\n";
echo "WebP: " . round($totalWebPSize / 1024 / 1024, 2) . "MB\n";
```

## Creating Responsive WebP Sets

Generate multiple sizes in WebP format:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$inputImage = 'photo.jpg';
$sizes = [
    'large' => ['width' => 1920, 'height' => 1280, 'quality' => 85],
    'medium' => ['width' => 1200, 'height' => 800, 'quality' => 80],
    'small' => ['width' => 600, 'height' => 400, 'quality' => 75],
];

foreach ($sizes as $name => $config) {
    // Resize first
    $tempPath = "temp-{$name}.jpg";
    $processor->resizeImage($inputImage, $tempPath, $config['width'], $config['height']);
    
    // Convert to WebP
    $webpPath = "responsive/{$name}.webp";
    $processor->convertToWebP($tempPath, $webpPath, $config['quality']);
    
    // Clean up
    unlink($tempPath);
    
    $size = round(filesize($webpPath) / 1024, 2);
    echo "Created {$name}.webp: {$size}KB\n";
}
```

Then use in HTML with fallback:

```html
<picture>
    <source media="(min-width: 1200px)" srcset="responsive/large.webp" type="image/webp">
    <source media="(min-width: 600px)" srcset="responsive/medium.webp" type="image/webp">
    <source srcset="responsive/small.webp" type="image/webp">
    <!-- Fallback for browsers that don't support WebP -->
    <img src="photo.jpg" alt="Responsive image">
</picture>
```

## WebP with Watermarks

Add watermarks before converting to WebP:

```php
$processor = new ImageProcessor();

// Add watermark
$processor->addWatermark(
    'photo.jpg',
    'temp-watermarked.jpg',
    'logo.png',
    'bottom-right',
    10
);

// Convert to WebP
$processor->convertToWebP('temp-watermarked.jpg', 'final.webp', 85);

// Clean up
unlink('temp-watermarked.jpg');
```

## Complete Web Optimization Workflow

Resize, watermark, and convert to WebP:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

function optimizeForWeb($inputPath, $outputPath, $watermarkPath = null) {
    $processor = new ImageProcessor();
    $temps = [];
    
    try {
        // Step 1: Resize to web dimensions
        $processor->resizeImage($inputPath, $temps[] = 'temp1.jpg', 1920, 1080);
        
        // Step 2: Add watermark (optional)
        if ($watermarkPath) {
            $processor->addWatermark(
                'temp1.jpg',
                $temps[] = 'temp2.jpg',
                $watermarkPath,
                'bottom-right',
                10
            );
            $source = 'temp2.jpg';
        } else {
            $source = 'temp1.jpg';
        }
        
        // Step 3: Convert to WebP
        $processor->convertToWebP($source, $outputPath, 85);
        
        echo "✅ Optimized: {$outputPath}\n";
        
    } finally {
        // Clean up temporary files
        foreach ($temps as $temp) {
            if (file_exists($temp)) unlink($temp);
        }
    }
}

// Use the function
optimizeForWeb('photo.jpg', 'optimized.webp', 'logo.png');
```

## Browser Support

WebP is supported by all modern browsers:

- ✅ Chrome 23+
- ✅ Firefox 65+
- ✅ Edge 18+
- ✅ Safari 14+ (macOS Big Sur)
- ✅ Opera 12.1+

### Providing Fallbacks

Always provide fallback images for older browsers:

```html
<picture>
    <!-- WebP for modern browsers -->
    <source srcset="image.webp" type="image/webp">
    <!-- JPEG fallback -->
    <img src="image.jpg" alt="Description">
</picture>
```

Or generate both formats:

```php
$processor = new ImageProcessor();

// Create WebP version
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);

// Keep JPEG version (or compress it)
$processor->compressToJpg('photo.jpg', 'photo-compressed.jpg', 150);
```

## Comparing File Sizes

Test different quality levels to find the best balance:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$inputImage = 'photo.jpg';
$originalSize = filesize($inputImage);

echo "Original JPEG: " . round($originalSize / 1024, 2) . "KB\n\n";

$qualities = [95, 90, 85, 80, 75, 70, 60, 50];

foreach ($qualities as $quality) {
    $outputPath = "test/quality-{$quality}.webp";
    $processor->convertToWebP($inputImage, $outputPath, $quality);
    
    $webpSize = filesize($outputPath);
    $savings = round((1 - $webpSize / $originalSize) * 100, 2);
    
    echo "Quality {$quality}: " . 
         round($webpSize / 1024, 2) . "KB " .
         "({$savings}% smaller)\n";
}
```

## Performance Benefits

### File Size Comparison

Typical savings when converting to WebP:

| Original Format | Average Savings |
|----------------|-----------------|
| JPEG | 25-35% |
| PNG (photos) | 50-70% |
| PNG (graphics) | 25-50% |
| GIF | 60-80% |

### Loading Speed

Smaller file sizes mean:
- ✅ Faster page load times
- ✅ Reduced bandwidth usage
- ✅ Better mobile experience
- ✅ Improved SEO rankings

## Error Handling

Handle conversion errors gracefully:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    // Check if input exists
    if (!file_exists('photo.jpg')) {
        throw new Exception("Input file not found");
    }
    
    // Check if WebP is supported
    if (!function_exists('imagewebp')) {
        throw new Exception("WebP support not available. Install GD with WebP support.");
    }
    
    // Convert
    $processor->convertToWebP('photo.jpg', 'photo.webp', 85);
    
    echo "✅ Conversion successful!";
    
} catch (Exception $e) {
    error_log("WebP conversion error: " . $e->getMessage());
    echo "❌ Failed to convert to WebP.";
}
```

## Best Practices

### 1. Choose Appropriate Quality

```php
// ✅ Good: Balanced quality for web
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);

// ❌ Overkill: Quality 100 defeats the purpose
$processor->convertToWebP('photo.jpg', 'photo.webp', 100);

// ⚠️ Too low: Visible quality loss
$processor->convertToWebP('photo.jpg', 'photo.webp', 30);
```

### 2. Always Provide Fallbacks

```php
// Create both WebP and JPEG
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);
$processor->compressToJpg('photo.jpg', 'photo-fallback.jpg', 150);
```

### 3. Test Quality Levels

```php
// Test different qualities to find the sweet spot
foreach ([95, 85, 75, 65] as $quality) {
    $processor->convertToWebP('photo.jpg', "test-{$quality}.webp", $quality);
    // Compare visually and by file size
}
```

## Method Signature

```php
public function convertToWebP(
    string $inputImagePath,   // Path to input image
    string $outputImagePath,  // Path to save WebP image
    int $quality = 80         // Quality 0-100 (default: 80)
): void
```

## See Also

- [Compressing Images](/guides/compressing/) - JPEG compression
- [Combined Operations](/guides/combined-operations/) - Efficient workflows
- [API Reference](/reference/convert-to-webp/) - Complete method documentation
