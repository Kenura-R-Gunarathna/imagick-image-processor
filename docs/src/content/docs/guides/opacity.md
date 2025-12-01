---
title: Adjusting Opacity
description: Learn how to add transparency to images and create semi-transparent effects.
---

The `addOpacity` method allows you to adjust the transparency of images, creating semi-transparent effects. The output is always saved as PNG to preserve transparency.

## Basic Usage

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$processor->addOpacity(
    'path/to/input.jpg',    // Input image
    'path/to/output.png',   // Output image (always PNG)
    50                       // Opacity percentage (0-100)
);
```

## Understanding Opacity

The opacity parameter works as follows:

- **100**: Fully opaque (no transparency)
- **75**: Slightly transparent
- **50**: Semi-transparent
- **25**: Mostly transparent
- **0**: Fully transparent (invisible)

```php
$processor = new ImageProcessor();

// Fully opaque (no change)
$processor->addOpacity('image.jpg', 'opaque.png', 100);

// Semi-transparent
$processor->addOpacity('image.jpg', 'semi.png', 50);

// Very transparent
$processor->addOpacity('image.jpg', 'transparent.png', 20);
```

:::note
The output is always saved as PNG because JPEG doesn't support transparency. Even if your input is JPEG, the output will be PNG.
:::

## Common Use Cases

### 1. Watermark Creation

Create semi-transparent watermarks:

```php
$processor = new ImageProcessor();

// Create a 50% transparent logo for watermarking
$processor->addOpacity('logo.png', 'logo-watermark.png', 50);

// Use it as a watermark
$processor->addWatermark(
    'photo.jpg',
    'watermarked.jpg',
    'logo-watermark.png',
    'bottom-right',
    10
);
```

### 2. Overlay Effects

Create overlay images for design:

```php
// Create a semi-transparent overlay
$processor->addOpacity('texture.jpg', 'overlay.png', 30);

// Layer it over another image (requires additional processing)
```

### 3. Background Images

Create subtle background images:

```php
// Make background image very transparent
$processor->addOpacity('pattern.jpg', 'bg-pattern.png', 15);
```

### 4. Fade Effects

Create faded versions of images:

```php
$processor = new ImageProcessor();

// Create multiple fade levels
$fadeLevels = [
    'light' => 90,
    'medium' => 70,
    'heavy' => 50,
    'extreme' => 30
];

foreach ($fadeLevels as $name => $opacity) {
    $processor->addOpacity(
        'image.jpg',
        "faded-{$name}.png",
        $opacity
    );
}
```

## Creating Transparent Watermarks

Step-by-step watermark creation:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Step 1: Resize logo to appropriate size
$processor->resizeImage('logo.png', 'logo-resized.png', 300, 300);

// Step 2: Make it semi-transparent
$processor->addOpacity('logo-resized.png', 'logo-watermark.png', 40);

// Step 3: Use as watermark on photos
$photos = glob('photos/*.jpg');

foreach ($photos as $photo) {
    $filename = basename($photo);
    $processor->addWatermark(
        $photo,
        "watermarked/{$filename}",
        'logo-watermark.png',
        'bottom-right',
        12
    );
}

// Clean up temporary files
unlink('logo-resized.png');

echo "Watermarking complete!";
```

## Batch Opacity Adjustment

Apply opacity to multiple images:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Configuration
$inputDir = 'images/';
$outputDir = 'transparent/';
$opacityLevel = 60;

// Create output directory
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Get all images
$images = glob($inputDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

foreach ($images as $image) {
    $filename = pathinfo($image, PATHINFO_FILENAME);
    $outputPath = $outputDir . $filename . '.png';
    
    try {
        $processor->addOpacity($image, $outputPath, $opacityLevel);
        echo "✅ Processed: {$filename}\n";
    } catch (Exception $e) {
        echo "❌ Error: {$filename} - " . $e->getMessage() . "\n";
    }
}

echo "Batch processing complete!";
```

## Progressive Transparency

Create a series of images with increasing transparency:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$inputImage = 'image.jpg';

// Create 10 versions with 10% opacity increments
for ($i = 1; $i <= 10; $i++) {
    $opacity = $i * 10;
    $outputPath = "progressive/opacity-{$opacity}.png";
    
    $processor->addOpacity($inputImage, $outputPath, $opacity);
    echo "Created version with {$opacity}% opacity\n";
}
```

## Format Considerations

### Input Formats

The method accepts various input formats:

```php
$processor = new ImageProcessor();

// JPEG input
$processor->addOpacity('photo.jpg', 'transparent.png', 50);

// PNG input (preserves existing transparency)
$processor->addOpacity('graphic.png', 'more-transparent.png', 50);

// GIF input
$processor->addOpacity('animation.gif', 'transparent.png', 50);
```

### Output Format

Output is always PNG:

```php
// Even with .jpg extension, output will be PNG
$processor->addOpacity('input.jpg', 'output.jpg', 50);
// Result: output.jpg will actually be a PNG file

// Best practice: use .png extension
$processor->addOpacity('input.jpg', 'output.png', 50);
```

:::tip
Always use `.png` extension for the output file to avoid confusion, as the method always creates PNG files.
:::

## Combining with Other Operations

### Resize and Add Opacity

```php
$processor = new ImageProcessor();

// Resize first
$processor->resizeImage('large.jpg', 'temp.jpg', 800, 600);

// Then add opacity
$processor->addOpacity('temp.jpg', 'final.png', 60);

// Clean up
unlink('temp.jpg');
```

### Create Transparent Watermark and Apply

```php
$processor = new ImageProcessor();

// Create transparent watermark
$processor->addOpacity('logo.png', 'watermark.png', 40);

// Apply to multiple images
$images = glob('photos/*.jpg');
foreach ($images as $image) {
    $output = 'watermarked/' . basename($image);
    $processor->addWatermark($image, $output, 'watermark.png', 'bottom-right', 10);
}
```

## Opacity Validation

The method automatically validates opacity values:

```php
$processor = new ImageProcessor();

// Values are clamped to 0-100 range
$processor->addOpacity('image.jpg', 'out1.png', -10);  // Treated as 0
$processor->addOpacity('image.jpg', 'out2.png', 150);  // Treated as 100
$processor->addOpacity('image.jpg', 'out3.png', 50);   // Used as-is
```

## Preserving Existing Transparency

When input is PNG with transparency:

```php
// If input.png has transparent areas
// The method will apply opacity to the entire image
// Including already transparent areas

$processor->addOpacity('transparent-logo.png', 'more-transparent.png', 50);

// Result: All pixels (including transparent ones) become more transparent
```

## Error Handling

Handle opacity operations safely:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    // Validate input
    $inputPath = 'image.jpg';
    if (!file_exists($inputPath)) {
        throw new Exception("Input file not found");
    }
    
    // Validate it's an image
    if (getimagesize($inputPath) === false) {
        throw new Exception("Invalid image file");
    }
    
    // Validate opacity value
    $opacity = 50;
    if ($opacity < 0 || $opacity > 100) {
        throw new Exception("Opacity must be between 0 and 100");
    }
    
    // Apply opacity
    $processor->addOpacity($inputPath, 'output.png', $opacity);
    
    // Verify output
    if (!file_exists('output.png')) {
        throw new Exception("Failed to create output file");
    }
    
    echo "Opacity applied successfully!";
    
} catch (Exception $e) {
    error_log("Opacity error: " . $e->getMessage());
    echo "Failed to apply opacity.";
}
```

## Performance Considerations

### 1. File Size

PNG files with transparency can be larger than JPEG:

```php
// Original JPEG: 500KB
$processor->addOpacity('photo.jpg', 'transparent.png', 50);
// Result PNG might be: 800KB or more

// Consider compressing if needed (though this removes transparency)
```

### 2. Processing Time

Adding opacity is generally fast:

```php
$start = microtime(true);

$processor->addOpacity('image.jpg', 'output.png', 50);

$duration = microtime(true) - $start;
echo "Processing took " . round($duration, 2) . " seconds\n";
```

### 3. Memory Usage

Large images require more memory:

```php
// For very large images, consider resizing first
$processor->resizeImage('huge.jpg', 'temp.jpg', 2000, 2000);
$processor->addOpacity('temp.jpg', 'output.png', 50);
unlink('temp.jpg');
```

## Practical Examples

### Example 1: Ghost Effect

Create a ghost/fade effect:

```php
$processor = new ImageProcessor();

// Create ghostly version
$processor->addOpacity('person.jpg', 'ghost.png', 30);
```

### Example 2: Layered Graphics

Prepare images for layering:

```php
// Create semi-transparent layers
$processor->addOpacity('layer1.jpg', 'layer1-trans.png', 80);
$processor->addOpacity('layer2.jpg', 'layer2-trans.png', 60);
$processor->addOpacity('layer3.jpg', 'layer3-trans.png', 40);
```

### Example 3: Subtle Watermarks

Create very subtle watermarks:

```php
// Create barely visible watermark
$processor->addOpacity('logo.png', 'subtle-watermark.png', 20);

// Apply to image
$processor->addWatermark(
    'photo.jpg',
    'watermarked.jpg',
    'subtle-watermark.png',
    'center',
    30  // Large but very transparent
);
```

## Method Signature

```php
public function addOpacity(
    string $inputImagePath,   // Path to input image
    string $outputImagePath,  // Path to save output (always PNG)
    int $opacityPercent       // Opacity 0-100 (0=transparent, 100=opaque)
): void
```

## See Also

- [Adding Watermarks](/guides/watermarks/) - Use transparent images as watermarks
- [API Reference](/reference/add-opacity/) - Complete method documentation
