---
title: Adding Watermarks
description: Learn how to protect your images with watermarks using flexible positioning and scaling options.
---

The `addWatermark` method allows you to add watermarks to your images with precise control over positioning and size. The watermark is automatically scaled based on the image's diagonal length, ensuring consistent appearance across different image sizes.

## Basic Usage

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$processor->addWatermark(
    'path/to/input.jpg',      // Input image
    'path/to/output.jpg',     // Output image
    'path/to/watermark.png',  // Watermark image
    'bottom-right',           // Position
    10                        // Scale (10% of diagonal)
);
```

## Watermark Positions

The library supports 9 preset positions:

### Corner Positions

```php
$processor = new ImageProcessor();

// Top corners
$processor->addWatermark('photo.jpg', 'tl.jpg', 'logo.png', 'top-left', 10);
$processor->addWatermark('photo.jpg', 'tr.jpg', 'logo.png', 'top-right', 10);

// Bottom corners
$processor->addWatermark('photo.jpg', 'bl.jpg', 'logo.png', 'bottom-left', 10);
$processor->addWatermark('photo.jpg', 'br.jpg', 'logo.png', 'bottom-right', 10);
```

### Edge Positions

```php
// Top, bottom, left, right (centered on edge)
$processor->addWatermark('photo.jpg', 'top.jpg', 'logo.png', 'top', 10);
$processor->addWatermark('photo.jpg', 'bottom.jpg', 'logo.png', 'bottom', 10);
$processor->addWatermark('photo.jpg', 'left.jpg', 'logo.png', 'left', 10);
$processor->addWatermark('photo.jpg', 'right.jpg', 'logo.png', 'right', 10);
```

### Center Position

```php
// Center of image (default)
$processor->addWatermark('photo.jpg', 'center.jpg', 'logo.png', 'center', 10);
// Or omit position parameter
$processor->addWatermark('photo.jpg', 'center.jpg', 'logo.png');
```

## Visual Position Guide

```
┌─────────────────────────────────┐
│ top-left      top      top-right│
│                                  │
│                                  │
│ left         center        right│
│                                  │
│                                  │
│bottom-left  bottom  bottom-right│
└─────────────────────────────────┘
```

## Watermark Scaling

The scale parameter determines the watermark size as a percentage of the image's diagonal length:

```php
$processor = new ImageProcessor();

// Small watermark (5% of diagonal)
$processor->addWatermark('photo.jpg', 'small.jpg', 'logo.png', 'bottom-right', 5);

// Medium watermark (10% of diagonal) - recommended
$processor->addWatermark('photo.jpg', 'medium.jpg', 'logo.png', 'bottom-right', 10);

// Large watermark (20% of diagonal)
$processor->addWatermark('photo.jpg', 'large.jpg', 'logo.png', 'bottom-right', 20);

// Extra large watermark (30% of diagonal)
$processor->addWatermark('photo.jpg', 'xlarge.jpg', 'logo.png', 'center', 30);
```

:::tip
**Why diagonal-based scaling?**

Using the diagonal ensures consistent watermark appearance across different image orientations and aspect ratios. A 10% diagonal watermark will look proportionally similar on both landscape and portrait images.
:::

## Common Watermarking Scenarios

### 1. Copyright Protection

Add a subtle copyright watermark:

```php
$processor = new ImageProcessor();

// Subtle corner watermark
$processor->addWatermark(
    'photo.jpg',
    'copyrighted.jpg',
    'copyright-logo.png',
    'bottom-right',
    8  // Small, unobtrusive
);
```

### 2. Brand Identity

Add your brand logo:

```php
// Prominent brand watermark
$processor->addWatermark(
    'product.jpg',
    'branded.jpg',
    'brand-logo.png',
    'bottom-left',
    12
);
```

### 3. Photography Watermarks

Protect your photography:

```php
// Photographer signature
$processor->addWatermark(
    'portrait.jpg',
    'signed.jpg',
    'signature.png',
    'bottom-right',
    15
);

// Studio logo
$processor->addWatermark(
    'studio-photo.jpg',
    'watermarked.jpg',
    'studio-logo.png',
    'bottom-center',
    10
);
```

### 4. Social Media Watermarks

Prepare images for social sharing:

```php
// Instagram watermark
$processor->addWatermark(
    'instagram-post.jpg',
    'watermarked.jpg',
    'social-logo.png',
    'top-right',
    8
);

// Facebook watermark
$processor->addWatermark(
    'facebook-post.jpg',
    'watermarked.jpg',
    'page-logo.png',
    'bottom-left',
    10
);
```

## Batch Watermarking

Apply watermarks to multiple images:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Configuration
$inputDir = 'photos/';
$outputDir = 'watermarked/';
$watermarkPath = 'assets/watermark.png';
$position = 'bottom-right';
$scale = 10;

// Create output directory
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Get all images
$images = glob($inputDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

foreach ($images as $image) {
    $filename = basename($image);
    $outputPath = $outputDir . $filename;
    
    try {
        $processor->addWatermark(
            $image,
            $outputPath,
            $watermarkPath,
            $position,
            $scale
        );
        echo "✅ Watermarked: {$filename}\n";
    } catch (Exception $e) {
        echo "❌ Error: {$filename} - " . $e->getMessage() . "\n";
    }
}

echo "Batch watermarking complete!";
```

## Multiple Watermarks

Add multiple watermarks to a single image:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Add logo in bottom-right
$processor->addWatermark(
    'photo.jpg',
    'temp1.jpg',
    'logo.png',
    'bottom-right',
    10
);

// Add copyright text in bottom-left
$processor->addWatermark(
    'temp1.jpg',
    'temp2.jpg',
    'copyright.png',
    'bottom-left',
    8
);

// Add website URL in top-right
$processor->addWatermark(
    'temp2.jpg',
    'final.jpg',
    'website.png',
    'top-right',
    6
);

// Clean up temporary files
unlink('temp1.jpg');
unlink('temp2.jpg');
```

## Creating Watermark Images

### Text Watermarks

Create text-based watermarks using image editing software or programmatically:

```php
<?php

// Create a simple text watermark using GD
$text = '© 2024 Your Name';
$font = __DIR__ . '/fonts/arial.ttf';
$fontSize = 24;

// Calculate text dimensions
$bbox = imagettfbbox($fontSize, 0, $font, $text);
$width = abs($bbox[4] - $bbox[0]) + 20;
$height = abs($bbox[5] - $bbox[1]) + 20;

// Create image
$image = imagecreatetruecolor($width, $height);

// Make background transparent
$transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
imagefill($image, 0, 0, $transparent);
imagesavealpha($image, true);

// Add white text with slight transparency
$white = imagecolorallocatealpha($image, 255, 255, 255, 30);
imagettftext($image, $fontSize, 0, 10, $height - 10, $white, $font, $text);

// Save watermark
imagepng($image, 'watermarks/copyright.png');
imagedestroy($image);

// Now use it
$processor = new ImageProcessor();
$processor->addWatermark(
    'photo.jpg',
    'watermarked.jpg',
    'watermarks/copyright.png',
    'bottom-right',
    15
);
```

### Logo Watermarks

Best practices for logo watermarks:

- Use PNG format with transparency
- Include padding around the logo
- Use white or light colors for dark images
- Use dark colors for light images
- Keep the logo simple and recognizable

## Watermark Opacity

For semi-transparent watermarks, prepare your watermark image with transparency:

```php
// Create a semi-transparent watermark using the addOpacity method
$processor = new ImageProcessor();

// Make logo 50% transparent
$processor->addOpacity('logo.png', 'logo-transparent.png', 50);

// Use the transparent logo as watermark
$processor->addWatermark(
    'photo.jpg',
    'watermarked.jpg',
    'logo-transparent.png',
    'bottom-right',
    10
);
```

## Combining with Other Operations

### Resize, Watermark, and Compress

```php
$processor = new ImageProcessor();

// Step 1: Resize
$processor->resizeImage('original.jpg', 'temp1.jpg', 1200, 800);

// Step 2: Add watermark
$processor->addWatermark('temp1.jpg', 'temp2.jpg', 'logo.png', 'bottom-right', 10);

// Step 3: Compress
$processor->compressToJpg('temp2.jpg', 'final.jpg', 150);

// Clean up
unlink('temp1.jpg');
unlink('temp2.jpg');
```

Or use the built-in combined method:

```php
// All in one step
$processor->resizeWatermarkAndCompress(
    'original.jpg',
    'final.jpg',
    1200,                    // width
    800,                     // height
    'logo.png',              // watermark
    'bottom-right',          // position
    10,                      // scale
    10,                      // heightPercent (deprecated, use scale)
    150                      // quality/target size
);
```

## Aspect Ratio Preservation

The watermark's aspect ratio is always preserved:

```php
// If your watermark is 200x100 (2:1 ratio)
// And the scale results in width of 120px
// The height will automatically be 60px to maintain the 2:1 ratio

$processor->addWatermark('photo.jpg', 'out.jpg', 'wide-logo.png', 'bottom', 10);
```

## Error Handling

Handle watermarking errors:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    // Validate input image
    if (!file_exists('photo.jpg')) {
        throw new Exception("Input image not found");
    }
    
    // Validate watermark
    if (!file_exists('logo.png')) {
        throw new Exception("Watermark image not found");
    }
    
    // Check if watermark is a valid image
    if (getimagesize('logo.png') === false) {
        throw new Exception("Invalid watermark image");
    }
    
    // Apply watermark
    $processor->addWatermark(
        'photo.jpg',
        'watermarked.jpg',
        'logo.png',
        'bottom-right',
        10
    );
    
    echo "Watermark applied successfully!";
    
} catch (Exception $e) {
    error_log("Watermark error: " . $e->getMessage());
    echo "Failed to apply watermark.";
}
```

## Performance Tips

### 1. Reuse Watermark Images

```php
// Don't create watermark images repeatedly
// Create once, reuse many times
$watermarkPath = 'assets/watermark.png';

foreach ($images as $image) {
    $processor->addWatermark($image, "out/{$image}", $watermarkPath, 'bottom-right', 10);
}
```

### 2. Optimize Watermark Size

```php
// Keep watermark images reasonably sized
// A 500x500px watermark is usually sufficient
// Larger watermarks slow down processing
```

### 3. Use Appropriate Formats

```php
// PNG for logos with transparency
// JPEG for photographic watermarks
// Avoid large, high-resolution watermark files
```

## Method Signature

```php
public function addWatermark(
    string $inputImagePath,      // Path to input image
    string $outputImagePath,     // Path to save watermarked image
    string $watermarkImagePath,  // Path to watermark image
    string $position = 'center', // Position: center, top, bottom, left, right,
                                 // top-left, top-right, bottom-left, bottom-right
    int $scalePercent = 10       // Size as % of image diagonal (1-100)
): void
```

## See Also

- [Adjusting Opacity](/guides/opacity/) - Create semi-transparent watermarks
- [Combined Operations](/guides/combined-operations/) - Watermark with other operations
- [API Reference](/reference/add-watermark/) - Complete method documentation
