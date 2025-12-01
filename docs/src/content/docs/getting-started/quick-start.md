---
title: Quick Start
description: Get up and running with Imagick Image Processor in minutes.
---

This guide will help you get started with the Imagick Image Processor library quickly. We'll cover the basics of each major feature with simple examples.

## Basic Setup

First, make sure you've [installed the library](/getting-started/installation/). Then, create a new PHP file and include the autoloader:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

// Create an instance
$processor = new ImageProcessor();
```

## Your First Image Operation

Let's start with a simple resize operation:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Resize an image to 800x600 (maintains aspect ratio)
$processor->resizeImage(
    'path/to/input.jpg',
    'path/to/output.jpg',
    800,
    600
);

echo "Image resized successfully!";
```

:::tip
The `resizeImage` method automatically maintains the aspect ratio of your image. If you specify 800x600 but your image is 1920x1080, it will be resized to 800x450 to preserve the original proportions.
:::

## Common Use Cases

### 1. Compress an Image

Reduce file size while maintaining quality:

```php
// Compress to approximately 100KB
$processor->compressToJpg(
    'input.jpg',
    'compressed.jpg',
    100  // Target size in KB
);
```

The method will automatically adjust the quality to reach the target file size.

### 2. Add a Watermark

Protect your images with a watermark:

```php
// Add watermark in the bottom-right corner
$processor->addWatermark(
    'input.jpg',
    'watermarked.jpg',
    'logo.png',
    'bottom-right',  // Position
    10               // Size (10% of diagonal)
);
```

**Available positions:**
- `center` (default)
- `top`, `bottom`, `left`, `right`
- `top-left`, `top-right`, `bottom-left`, `bottom-right`

### 3. Adjust Image Opacity

Make an image semi-transparent:

```php
// Set opacity to 50%
$processor->addOpacity(
    'input.png',
    'transparent.png',
    50  // Opacity percentage (0-100)
);
```

:::note
The output will always be saved as PNG to preserve transparency.
:::

## Chaining Operations

For more complex workflows, you can perform multiple operations sequentially:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Step 1: Resize
$processor->resizeImage('original.jpg', 'temp.jpg', 1200, 800);

// Step 2: Add watermark
$processor->addWatermark('temp.jpg', 'temp2.jpg', 'logo.png', 'bottom-right', 8);

// Step 3: Compress
$processor->compressToJpg('temp2.jpg', 'final.jpg', 150);

// Clean up temporary files
unlink('temp.jpg');
unlink('temp2.jpg');

echo "Processing complete!";
```

## Handling Errors

Always wrap your operations in try-catch blocks for production code:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    $processor->resizeImage('input.jpg', 'output.jpg', 800, 600);
    echo "Success!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    // Log the error or handle it appropriately
}
```

## Working with Different Image Formats

The library supports JPEG, PNG, and GIF formats:

```php
// JPEG
$processor->resizeImage('photo.jpg', 'resized.jpg', 800, 600);

// PNG (preserves transparency)
$processor->resizeImage('graphic.png', 'resized.png', 800, 600);

// GIF (preserves animation on first frame)
$processor->resizeImage('animation.gif', 'resized.gif', 800, 600);
```

## Complete Example

Here's a complete example that processes user-uploaded images:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Configuration
$uploadDir = __DIR__ . '/uploads/';
$processedDir = __DIR__ . '/processed/';
$watermarkPath = __DIR__ . '/assets/watermark.png';

// Ensure directories exist
if (!is_dir($processedDir)) {
    mkdir($processedDir, 0755, true);
}

// Process an uploaded image
if (isset($_FILES['image'])) {
    $inputPath = $uploadDir . $_FILES['image']['name'];
    $outputPath = $processedDir . 'processed_' . $_FILES['image']['name'];
    
    try {
        // Move uploaded file
        move_uploaded_file($_FILES['image']['tmp_name'], $inputPath);
        
        // Resize to max 1920x1080
        $processor->resizeImage($inputPath, $outputPath, 1920, 1080);
        
        // Add watermark
        $processor->addWatermark(
            $outputPath, 
            $outputPath, 
            $watermarkPath, 
            'bottom-right', 
            10
        );
        
        // Compress to ~200KB
        $processor->compressToJpg($outputPath, $outputPath, 200);
        
        echo "Image processed successfully!";
        
        // Clean up original
        unlink($inputPath);
        
    } catch (Exception $e) {
        echo "Error processing image: " . $e->getMessage();
    }
}
```

## Next Steps

Now that you understand the basics, explore more detailed guides:

- [Resizing Images](/guides/resizing/) - Advanced resizing techniques
- [Compressing Images](/guides/compressing/) - Optimize file sizes
- [Adding Watermarks](/guides/watermarks/) - Protect your images
- [Adjusting Opacity](/guides/opacity/) - Work with transparency
- [Combined Operations](/guides/combined-operations/) - Efficient workflows

Or check out the [API Reference](/reference/imageprocessor/) for complete method documentation.
