---
title: Resizing Images
description: Learn how to resize images while maintaining aspect ratios and quality.
---

The `resizeImage` method is one of the core features of the Imagick Image Processor library. It allows you to resize images while automatically maintaining their aspect ratio.

## Basic Usage

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$processor->resizeImage(
    'path/to/input.jpg',    // Input image path
    'path/to/output.jpg',   // Output image path
    800,                     // Target width
    600                      // Target height
);
```

## How Aspect Ratio Preservation Works

The library automatically calculates the best dimensions to maintain your image's aspect ratio:

```php
// Original image: 1920x1080 (16:9 ratio)
// Requested: 800x600

// Result: 800x450 (maintains 16:9 ratio)
$processor->resizeImage('wide.jpg', 'resized.jpg', 800, 600);

// Original image: 1080x1920 (9:16 ratio - portrait)
// Requested: 800x600

// Result: 337x600 (maintains 9:16 ratio)
$processor->resizeImage('tall.jpg', 'resized.jpg', 800, 600);
```

:::tip
The method uses the **smaller dimension** to ensure the entire image fits within your specified bounds while maintaining the original aspect ratio.
:::

## Common Resize Scenarios

### 1. Thumbnail Generation

Create small thumbnails for galleries:

```php
$processor = new ImageProcessor();

// Create 150x150 thumbnail (actual size depends on aspect ratio)
$processor->resizeImage(
    'photos/original.jpg',
    'thumbnails/thumb.jpg',
    150,
    150
);
```

### 2. Web Optimization

Resize images for web display:

```php
// Standard web size
$processor->resizeImage('large.jpg', 'web.jpg', 1200, 800);

// Mobile-friendly size
$processor->resizeImage('large.jpg', 'mobile.jpg', 600, 400);

// Retina display (2x)
$processor->resizeImage('large.jpg', 'retina.jpg', 2400, 1600);
```

### 3. Social Media Sizes

Prepare images for different social media platforms:

```php
$processor = new ImageProcessor();

// Instagram post (1:1)
$processor->resizeImage('photo.jpg', 'instagram.jpg', 1080, 1080);

// Facebook cover (820:312 ratio)
$processor->resizeImage('cover.jpg', 'fb-cover.jpg', 820, 312);

// Twitter header (1500:500 ratio)
$processor->resizeImage('header.jpg', 'twitter.jpg', 1500, 500);

// YouTube thumbnail (1280:720 ratio)
$processor->resizeImage('video-thumb.jpg', 'youtube.jpg', 1280, 720);
```

## Batch Resizing

Process multiple images at once:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Configuration
$inputDir = 'originals/';
$outputDir = 'resized/';
$targetWidth = 1200;
$targetHeight = 800;

// Create output directory if it doesn't exist
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Get all images
$images = glob($inputDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

foreach ($images as $image) {
    $filename = basename($image);
    $outputPath = $outputDir . $filename;
    
    try {
        $processor->resizeImage($image, $outputPath, $targetWidth, $targetHeight);
        echo "✅ Resized: $filename\n";
    } catch (Exception $e) {
        echo "❌ Error resizing $filename: " . $e->getMessage() . "\n";
    }
}

echo "Batch processing complete!";
```

## Responsive Image Sets

Create multiple sizes for responsive images:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$inputImage = 'original.jpg';
$sizes = [
    'small' => [480, 320],
    'medium' => [768, 512],
    'large' => [1200, 800],
    'xlarge' => [1920, 1280]
];

foreach ($sizes as $name => $dimensions) {
    list($width, $height) = $dimensions;
    $outputPath = "responsive/{$name}.jpg";
    
    $processor->resizeImage($inputImage, $outputPath, $width, $height);
    echo "Created {$name} version: {$width}x{$height}\n";
}
```

Then use in HTML:

```html
<picture>
    <source media="(min-width: 1200px)" srcset="responsive/xlarge.jpg">
    <source media="(min-width: 768px)" srcset="responsive/large.jpg">
    <source media="(min-width: 480px)" srcset="responsive/medium.jpg">
    <img src="responsive/small.jpg" alt="Responsive image">
</picture>
```

## Preserving Image Quality

The `resizeImage` method uses high-quality resampling:

```php
// The library uses imagescale() which provides high-quality results
// No additional quality parameters needed for resizing
$processor->resizeImage('input.jpg', 'output.jpg', 800, 600);

// For quality control during compression, use compressToJpg() after resizing
$processor->resizeImage('input.jpg', 'temp.jpg', 800, 600);
$processor->compressToJpg('temp.jpg', 'output.jpg', 150); // 150KB target
unlink('temp.jpg');
```

## Working with Different Formats

### JPEG Images

```php
// Standard JPEG resize
$processor->resizeImage('photo.jpg', 'resized.jpg', 1024, 768);
```

### PNG Images

```php
// PNG with transparency preserved
$processor->resizeImage('logo.png', 'resized-logo.png', 400, 300);
```

### GIF Images

```php
// GIF (first frame only)
$processor->resizeImage('animation.gif', 'resized.gif', 600, 400);
```

:::note
Animated GIFs will have only their first frame resized. For full animation support, consider using specialized GIF processing libraries.
:::

## Error Handling

Always handle potential errors:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    // Check if input file exists
    $inputPath = 'photos/original.jpg';
    if (!file_exists($inputPath)) {
        throw new Exception("Input file not found: $inputPath");
    }
    
    // Check if input is a valid image
    $imageInfo = getimagesize($inputPath);
    if ($imageInfo === false) {
        throw new Exception("Invalid image file: $inputPath");
    }
    
    // Perform resize
    $processor->resizeImage($inputPath, 'output.jpg', 800, 600);
    
    echo "Image resized successfully!";
    
} catch (Exception $e) {
    error_log("Resize error: " . $e->getMessage());
    echo "Failed to resize image. Please try again.";
}
```

## Performance Tips

### 1. Don't Upscale

Avoid making images larger than their original size:

```php
// Get original dimensions
list($origWidth, $origHeight) = getimagesize('input.jpg');

// Only resize if larger than target
$targetWidth = 1200;
$targetHeight = 800;

if ($origWidth > $targetWidth || $origHeight > $targetHeight) {
    $processor->resizeImage('input.jpg', 'output.jpg', $targetWidth, $targetHeight);
} else {
    // Just copy the file
    copy('input.jpg', 'output.jpg');
}
```

### 2. Process in Background

For large batches, use background processing:

```php
// Example using a simple queue
$queue = [
    ['input' => 'img1.jpg', 'output' => 'out1.jpg', 'width' => 800, 'height' => 600],
    ['input' => 'img2.jpg', 'output' => 'out2.jpg', 'width' => 800, 'height' => 600],
    // ... more images
];

foreach ($queue as $job) {
    $processor->resizeImage(
        $job['input'],
        $job['output'],
        $job['width'],
        $job['height']
    );
}
```

### 3. Clean Up Temporary Files

Always remove temporary files:

```php
$tempFile = 'temp_' . uniqid() . '.jpg';

try {
    $processor->resizeImage('input.jpg', $tempFile, 800, 600);
    // ... do something with $tempFile
} finally {
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
}
```

## Method Signature

```php
public function resizeImage(
    string $inputImagePath,   // Path to input image
    string $outputImagePath,  // Path to save resized image
    int $width,               // Target width in pixels
    int $height               // Target height in pixels
): void
```

## See Also

- [Compress Images](/guides/compressing/) - Reduce file sizes after resizing
- [Combined Operations](/guides/combined-operations/) - Resize and compress in one step
- [API Reference](/reference/resize-image/) - Complete method documentation
