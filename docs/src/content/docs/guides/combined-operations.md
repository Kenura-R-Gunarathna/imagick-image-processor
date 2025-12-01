---
title: Combined Operations
description: Learn how to efficiently combine multiple image processing operations in a single workflow.
---

The Imagick Image Processor library provides methods that combine multiple operations for efficient image processing workflows. This guide covers both the built-in combined methods and how to create your own efficient processing pipelines.

## Built-In Combined Methods

The library includes two built-in methods that combine multiple operations. Note that these methods are available in the source code but may not be documented in the README.

### Resize and Compress

Combines resizing and compression in a single operation:

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Resize to 1200x800 and compress to ~150KB
$processor->resizeAndCompress(
    'input.jpg',
    'output.jpg',
    1200,           // Width
    800,            // Height
    150             // Target size in KB
);
```

This is equivalent to:

```php
$processor->resizeImage('input.jpg', 'temp.jpg', 1200, 800);
$processor->compressToJpg('temp.jpg', 'output.jpg', 150);
unlink('temp.jpg');
```

### Resize, Watermark, and Compress

Combines all three major operations:

```php
$processor = new ImageProcessor();

// Complete processing pipeline
$processor->resizeWatermarkAndCompress(
    'input.jpg',
    'output.jpg',
    1200,                // Width
    800,                 // Height
    'watermark.png',     // Watermark image
    'bottom-right',      // Position
    10,                  // Width percent (scale)
    10,                  // Height percent (deprecated, use scale)
    150                  // Target size in KB
);
```

This is equivalent to:

```php
$processor->resizeImage('input.jpg', 'temp1.jpg', 1200, 800);
$processor->addWatermark('temp1.jpg', 'temp2.jpg', 'watermark.png', 'bottom-right', 10);
$processor->compressToJpg('temp2.jpg', 'output.jpg', 150);
unlink('temp1.jpg');
unlink('temp2.jpg');
```

## Common Workflows

### 1. Web Image Optimization

Prepare images for web use:

```php
$processor = new ImageProcessor();

// Resize, watermark, and compress for web
$processor->resizeWatermarkAndCompress(
    'original.jpg',
    'web-optimized.jpg',
    1920,               // Max width
    1080,               // Max height
    'logo.png',         // Watermark
    'bottom-right',     // Position
    8,                  // Small watermark
    8,
    200                 // 200KB target
);
```

### 2. Social Media Preparation

Process images for social media:

```php
$processor = new ImageProcessor();

// Instagram post (1080x1080)
$processor->resizeWatermarkAndCompress(
    'photo.jpg',
    'instagram.jpg',
    1080,
    1080,
    'social-logo.png',
    'bottom-left',
    10,
    10,
    150
);

// Facebook cover (820x312)
$processor->resizeAndCompress(
    'cover.jpg',
    'facebook-cover.jpg',
    820,
    312,
    100
);
```

### 3. E-commerce Product Images

Standardize product photos:

```php
$processor = new ImageProcessor();

// Main product image
$processor->resizeWatermarkAndCompress(
    'product-photo.jpg',
    'product-main.jpg',
    1200,
    1200,
    'store-logo.png',
    'bottom-right',
    5,                  // Small, subtle watermark
    5,
    200
);

// Thumbnail
$processor->resizeAndCompress(
    'product-photo.jpg',
    'product-thumb.jpg',
    300,
    300,
    50                  // Small file size
);
```

### 4. Portfolio Images

Prepare images for portfolio websites:

```php
$processor = new ImageProcessor();

// Full-size portfolio image
$processor->resizeWatermarkAndCompress(
    'portfolio-item.jpg',
    'portfolio-full.jpg',
    2000,
    1500,
    'signature.png',
    'bottom-right',
    12,
    12,
    300
);

// Gallery thumbnail
$processor->resizeAndCompress(
    'portfolio-item.jpg',
    'portfolio-thumb.jpg',
    400,
    300,
    75
);
```

## Batch Processing with Combined Operations

Process multiple images efficiently:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Configuration
$inputDir = 'originals/';
$outputDir = 'processed/';
$watermark = 'assets/watermark.png';

// Processing settings
$settings = [
    'width' => 1200,
    'height' => 800,
    'position' => 'bottom-right',
    'scale' => 10,
    'targetSize' => 150
];

// Create output directory
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Get all images
$images = glob($inputDir . '*.{jpg,jpeg}', GLOB_BRACE);

$processed = 0;
$failed = 0;

foreach ($images as $image) {
    $filename = basename($image);
    $outputPath = $outputDir . $filename;
    
    try {
        $processor->resizeWatermarkAndCompress(
            $image,
            $outputPath,
            $settings['width'],
            $settings['height'],
            $watermark,
            $settings['position'],
            $settings['scale'],
            $settings['scale'],
            $settings['targetSize']
        );
        
        $processed++;
        echo "✅ Processed: {$filename}\n";
        
    } catch (Exception $e) {
        $failed++;
        echo "❌ Failed: {$filename} - " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Summary:\n";
echo "   Processed: {$processed}\n";
echo "   Failed: {$failed}\n";
echo "   Total: " . count($images) . "\n";
```

## Creating Custom Workflows

Build your own processing pipelines:

### Example 1: Resize, Opacity, and Watermark

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

function processWithOpacity($inputPath, $outputPath) {
    $processor = new ImageProcessor();
    
    // Step 1: Resize
    $processor->resizeImage($inputPath, 'temp1.jpg', 1200, 800);
    
    // Step 2: Create transparent watermark
    $processor->addOpacity('logo.png', 'temp-watermark.png', 40);
    
    // Step 3: Apply watermark
    $processor->addWatermark('temp1.jpg', 'temp2.jpg', 'temp-watermark.png', 'center', 20);
    
    // Step 4: Compress
    $processor->compressToJpg('temp2.jpg', $outputPath, 150);
    
    // Clean up
    unlink('temp1.jpg');
    unlink('temp2.jpg');
    unlink('temp-watermark.png');
}

// Use the custom function
processWithOpacity('input.jpg', 'output.jpg');
```

### Example 2: Multiple Watermarks Workflow

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

function addMultipleWatermarks($inputPath, $outputPath) {
    $processor = new ImageProcessor();
    
    // Resize first
    $processor->resizeImage($inputPath, 'temp1.jpg', 1920, 1080);
    
    // Add logo watermark
    $processor->addWatermark('temp1.jpg', 'temp2.jpg', 'logo.png', 'bottom-right', 10);
    
    // Add copyright text
    $processor->addWatermark('temp2.jpg', 'temp3.jpg', 'copyright.png', 'bottom-left', 8);
    
    // Add website URL
    $processor->addWatermark('temp3.jpg', 'temp4.jpg', 'website.png', 'top-right', 6);
    
    // Final compression
    $processor->compressToJpg('temp4.jpg', $outputPath, 200);
    
    // Clean up
    foreach (['temp1.jpg', 'temp2.jpg', 'temp3.jpg', 'temp4.jpg'] as $temp) {
        if (file_exists($temp)) unlink($temp);
    }
}

// Use the custom function
addMultipleWatermarks('photo.jpg', 'final.jpg');
```

### Example 3: Responsive Image Set Generator

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

function generateResponsiveSet($inputPath, $baseName) {
    $processor = new ImageProcessor();
    
    $sizes = [
        'xlarge' => ['width' => 1920, 'height' => 1280, 'size' => 300],
        'large'  => ['width' => 1200, 'height' => 800,  'size' => 200],
        'medium' => ['width' => 768,  'height' => 512,  'size' => 150],
        'small'  => ['width' => 480,  'height' => 320,  'size' => 100],
    ];
    
    foreach ($sizes as $name => $config) {
        $outputPath = "responsive/{$baseName}-{$name}.jpg";
        
        $processor->resizeWatermarkAndCompress(
            $inputPath,
            $outputPath,
            $config['width'],
            $config['height'],
            'watermark.png',
            'bottom-right',
            8,
            8,
            $config['size']
        );
        
        echo "Created {$name}: {$outputPath}\n";
    }
}

// Generate responsive set
generateResponsiveSet('photo.jpg', 'hero');
```

## Workflow Optimization Tips

### 1. Order of Operations

For best results, follow this order:

```php
// ✅ Optimal order:
// 1. Resize (reduces data to process)
// 2. Add watermark (on smaller image)
// 3. Compress (final optimization)

$processor->resizeImage('input.jpg', 'temp1.jpg', 1200, 800);
$processor->addWatermark('temp1.jpg', 'temp2.jpg', 'logo.png', 'bottom-right', 10);
$processor->compressToJpg('temp2.jpg', 'output.jpg', 150);

// ❌ Suboptimal order:
// Processing large images is slower
$processor->addWatermark('input.jpg', 'temp1.jpg', 'logo.png', 'bottom-right', 10);
$processor->resizeImage('temp1.jpg', 'temp2.jpg', 1200, 800);
$processor->compressToJpg('temp2.jpg', 'output.jpg', 150);
```

### 2. Minimize Temporary Files

Use efficient cleanup:

```php
$temps = [];

try {
    $processor->resizeImage('input.jpg', $temps[] = 'temp1.jpg', 1200, 800);
    $processor->addWatermark('temp1.jpg', $temps[] = 'temp2.jpg', 'logo.png', 'bottom-right', 10);
    $processor->compressToJpg('temp2.jpg', 'output.jpg', 150);
} finally {
    // Clean up all temporary files
    foreach ($temps as $temp) {
        if (file_exists($temp)) unlink($temp);
    }
}
```

### 3. Reuse Watermarks

Don't recreate watermarks repeatedly:

```php
// ✅ Create watermark once
$processor->addOpacity('logo.png', 'watermark-reusable.png', 50);

// Reuse for multiple images
foreach ($images as $image) {
    $processor->addWatermark($image, "out/{$image}", 'watermark-reusable.png', 'bottom-right', 10);
}

// ❌ Don't recreate for each image
foreach ($images as $image) {
    $processor->addOpacity('logo.png', 'temp-watermark.png', 50);
    $processor->addWatermark($image, "out/{$image}", 'temp-watermark.png', 'bottom-right', 10);
    unlink('temp-watermark.png');
}
```

## Error Handling in Workflows

Handle errors gracefully in complex workflows:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

function processImageSafely($inputPath, $outputPath) {
    $processor = new ImageProcessor();
    $temps = [];
    
    try {
        // Validate input
        if (!file_exists($inputPath)) {
            throw new Exception("Input file not found: {$inputPath}");
        }
        
        if (getimagesize($inputPath) === false) {
            throw new Exception("Invalid image file: {$inputPath}");
        }
        
        // Process with error handling at each step
        $processor->resizeImage($inputPath, $temps[] = 'temp1.jpg', 1200, 800);
        
        if (!file_exists('temp1.jpg')) {
            throw new Exception("Resize failed");
        }
        
        $processor->addWatermark('temp1.jpg', $temps[] = 'temp2.jpg', 'logo.png', 'bottom-right', 10);
        
        if (!file_exists('temp2.jpg')) {
            throw new Exception("Watermark failed");
        }
        
        $processor->compressToJpg('temp2.jpg', $outputPath, 150);
        
        if (!file_exists($outputPath)) {
            throw new Exception("Compression failed");
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Processing error: " . $e->getMessage());
        return false;
        
    } finally {
        // Always clean up temporary files
        foreach ($temps as $temp) {
            if (file_exists($temp)) {
                unlink($temp);
            }
        }
    }
}

// Use the safe function
if (processImageSafely('input.jpg', 'output.jpg')) {
    echo "✅ Processing successful!";
} else {
    echo "❌ Processing failed. Check logs.";
}
```

## Performance Monitoring

Track processing performance:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

function processWithTiming($inputPath, $outputPath) {
    $processor = new ImageProcessor();
    $timings = [];
    
    // Resize
    $start = microtime(true);
    $processor->resizeImage($inputPath, 'temp1.jpg', 1200, 800);
    $timings['resize'] = microtime(true) - $start;
    
    // Watermark
    $start = microtime(true);
    $processor->addWatermark('temp1.jpg', 'temp2.jpg', 'logo.png', 'bottom-right', 10);
    $timings['watermark'] = microtime(true) - $start;
    
    // Compress
    $start = microtime(true);
    $processor->compressToJpg('temp2.jpg', $outputPath, 150);
    $timings['compress'] = microtime(true) - $start;
    
    // Clean up
    unlink('temp1.jpg');
    unlink('temp2.jpg');
    
    // Report
    $total = array_sum($timings);
    echo "Processing completed in " . round($total, 2) . "s\n";
    echo "  Resize: " . round($timings['resize'], 2) . "s\n";
    echo "  Watermark: " . round($timings['watermark'], 2) . "s\n";
    echo "  Compress: " . round($timings['compress'], 2) . "s\n";
}

processWithTiming('large-image.jpg', 'output.jpg');
```

## See Also

- [Resizing Images](/guides/resizing/) - Detailed resize guide
- [Compressing Images](/guides/compressing/) - Compression techniques
- [Adding Watermarks](/guides/watermarks/) - Watermark positioning
- [Adjusting Opacity](/guides/opacity/) - Transparency effects
- [API Reference](/reference/imageprocessor/) - Complete API documentation
