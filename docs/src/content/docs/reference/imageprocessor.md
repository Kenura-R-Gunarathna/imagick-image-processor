---
title: ImageProcessor Class
description: Complete API reference for the ImageProcessor class.
---

The `ImageProcessor` class is the main class of the Imagick Image Processor library. It provides methods for resizing, compressing, watermarking, and adjusting opacity of images.

## Namespace

```php
Kenura\Imagick\ImageProcessor
```

## Class Synopsis

```php
class ImageProcessor
{
    // Public Methods
    public function resizeImage(
        string $inputImagePath,
        string $outputImagePath,
        int $width,
        int $height
    ): void;

    public function compressToJpg(
        string $inputImagePath,
        string $outputImagePath,
        int $targetFileSize = 100,
        int $quality = 80
    ): void;

    public function addWatermark(
        string $inputImagePath,
        string $outputImagePath,
        string $watermarkImagePath,
        string $position = 'center',
        int $scalePercent = 10
    ): void;

    public function addOpacity(
        string $inputImagePath,
        string $outputImagePath,
        int $opacityPercent
    ): void;

    public function convertToWebP(
        string $inputImagePath,
        string $outputImagePath,
        int $quality = 80
    ): void;

    // Private Methods
    private function openImage(string $imagePath): resource;
}
```

## Installation

```bash
composer require kenura/imagick
```

## Basic Usage

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

// Create an instance
$processor = new ImageProcessor();

// Use the methods
$processor->resizeImage('input.jpg', 'output.jpg', 800, 600);
```

## Public Methods

### resizeImage()

Resizes an image while maintaining aspect ratio.

```php
public function resizeImage(
    string $inputImagePath,
    string $outputImagePath,
    int $width,
    int $height
): void
```

**Parameters:**
- `$inputImagePath` - Path to the input image
- `$outputImagePath` - Path where the resized image will be saved
- `$width` - Target width in pixels
- `$height` - Target height in pixels

**Returns:** void

**Throws:** Exception if image processing fails

**Example:**
```php
$processor->resizeImage('photo.jpg', 'resized.jpg', 1200, 800);
```

[Full documentation →](/reference/resize-image/)

---

### compressToJpg()

Compresses an image to a target file size.

```php
public function compressToJpg(
    string $inputImagePath,
    string $outputImagePath,
    int $targetFileSize = 100,
    int $quality = 80
): void
```

**Parameters:**
- `$inputImagePath` - Path to the input image
- `$outputImagePath` - Path where the compressed image will be saved
- `$targetFileSize` - Target file size in KB (default: 100)
- `$quality` - Starting quality level 0-100 (default: 80)

**Returns:** void

**Throws:** Exception if image processing fails

**Example:**
```php
$processor->compressToJpg('large.jpg', 'compressed.jpg', 150, 85);
```

[Full documentation →](/reference/compress-to-jpg/)

---

### addWatermark()

Adds a watermark to an image with flexible positioning.

```php
public function addWatermark(
    string $inputImagePath,
    string $outputImagePath,
    string $watermarkImagePath,
    string $position = 'center',
    int $scalePercent = 10
): void
```

**Parameters:**
- `$inputImagePath` - Path to the input image
- `$outputImagePath` - Path where the watermarked image will be saved
- `$watermarkImagePath` - Path to the watermark image
- `$position` - Position of watermark: `center`, `top`, `bottom`, `left`, `right`, `top-left`, `top-right`, `bottom-left`, `bottom-right` (default: `center`)
- `$scalePercent` - Watermark size as percentage of image diagonal (default: 10)

**Returns:** void

**Throws:** Exception if image processing fails

**Example:**
```php
$processor->addWatermark(
    'photo.jpg',
    'watermarked.jpg',
    'logo.png',
    'bottom-right',
    12
);
```

[Full documentation →](/reference/add-watermark/)

---

### addOpacity()

Adjusts the opacity/transparency of an image.

```php
public function addOpacity(
    string $inputImagePath,
    string $outputImagePath,
    int $opacityPercent
): void
```

**Parameters:**
- `$inputImagePath` - Path to the input image
- `$outputImagePath` - Path where the output image will be saved (always PNG)
- `$opacityPercent` - Opacity level 0-100 (0=transparent, 100=opaque)

**Returns:** void

**Throws:** Exception if image processing fails

**Example:**
```php
$processor->addOpacity('image.jpg', 'transparent.png', 50);
```

[Full documentation →](/reference/add-opacity/)

---

### convertToWebP()

Converts images to modern WebP format for superior web performance.

```php
public function convertToWebP(
    string $inputImagePath,
    string $outputImagePath,
    int $quality = 80
): void
```

**Parameters:**
- `$inputImagePath` - Path to the input image
- `$outputImagePath` - Path where the WebP image will be saved
- `$quality` - Quality level 0-100 (default: 80)

**Returns:** void

**Throws:** Exception if image processing fails

**Example:**
```php
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);
```

[Full documentation →](/reference/convert-to-webp/)

## Private Methods

### openImage()

Opens an image file and returns a GD resource. Automatically detects the image type and uses the appropriate function.

```php
private function openImage(string $imagePath): resource
```

**Parameters:**
- `$imagePath` - Path to the image file

**Returns:** GD image resource

**Throws:** Exception if image type is unsupported

**Supported Formats:**
- JPEG (`.jpg`, `.jpeg`)
- PNG (`.png`)
- GIF (`.gif`)

## Supported Image Formats

### Input Formats

The library supports the following input formats:

| Format | Extension | Notes |
|--------|-----------|-------|
| JPEG | `.jpg`, `.jpeg` | Fully supported |
| PNG | `.png` | Transparency preserved where applicable |
| GIF | `.gif` | First frame only (animations not preserved) |

### Output Formats

Output format depends on the method used:

| Method | Output Format |
|--------|---------------|
| `resizeImage()` | Same as input |
| `compressToJpg()` | JPEG only |
| `addWatermark()` | JPEG |
| `addOpacity()` | PNG only |

## Error Handling

All public methods may throw exceptions. Always wrap calls in try-catch blocks:

```php
<?php

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    $processor->resizeImage('input.jpg', 'output.jpg', 800, 600);
    echo "Success!";
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo "Processing failed.";
}
```

## Common Exceptions

- **File not found**: Input image or watermark doesn't exist
- **Invalid image**: File is not a valid image
- **Unsupported format**: Image format not supported
- **Processing failed**: General processing error

## Complete Example

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    // Resize
    $processor->resizeImage('original.jpg', 'temp1.jpg', 1200, 800);
    echo "✅ Resized\n";
    
    // Add watermark
    $processor->addWatermark(
        'temp1.jpg',
        'temp2.jpg',
        'watermark.png',
        'bottom-right',
        10
    );
    echo "✅ Watermarked\n";
    
    // Compress
    $processor->compressToJpg('temp2.jpg', 'final.jpg', 150);
    echo "✅ Compressed\n";
    
    // Clean up
    unlink('temp1.jpg');
    unlink('temp2.jpg');
    
    echo "✅ Processing complete!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
```

## Requirements

- PHP 8.1 or higher
- `ext-imagick` extension version 3.7 or higher
- GD library (included with most PHP installations)

## See Also

- [Installation Guide](/getting-started/installation/)
- [Quick Start](/getting-started/quick-start/)
- [Guides](/guides/resizing/)
- [Individual Method References](/reference/resize-image/)
