---
title: convertToWebP()
description: API reference for the convertToWebP() method.
---

Converts images to the modern WebP format with configurable quality settings.

## Signature

```php
public function convertToWebP(
    string $inputImagePath,
    string $outputImagePath,
    int $quality = 80
): void
```

## Parameters

### `$inputImagePath` (string, required)
Path to the input image file. Supports JPEG, PNG, and GIF formats.

### `$outputImagePath` (string, required)
Path where the WebP image will be saved. Should use `.webp` extension.

### `$quality` (int, optional)
Quality level (0-100). Default: `80`

- Higher values = better quality, larger file
- Lower values = worse quality, smaller file
- Recommended: 75-90 for most use cases

## Return Value

Returns `void`. Creates the output WebP file.

## Behavior

### Conversion Process

1. Opens the input image (JPEG, PNG, or GIF)
2. Validates quality parameter (0-100)
3. Converts to WebP format with specified quality
4. Saves to output path
5. Cleans up resources

### Transparency Handling

- PNG with transparency → WebP with transparency (preserved)
- JPEG → WebP (opaque)
- GIF → WebP (first frame only, transparency preserved)

### Quality vs. File Size

WebP provides excellent compression:
- Quality 90: ~30% smaller than JPEG at same quality
- Quality 80: ~35% smaller than JPEG
- Quality 70: ~40% smaller than JPEG

## Examples

### Basic Conversion

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Convert with default quality (80)
$processor->convertToWebP('photo.jpg', 'photo.webp');
```

### Custom Quality

```php
// High quality
$processor->convertToWebP('photo.jpg', 'high-quality.webp', 95);

// Balanced (recommended)
$processor->convertToWebP('photo.jpg', 'balanced.webp', 85);

// Smaller file
$processor->convertToWebP('photo.jpg', 'compressed.webp', 70);
```

### Convert PNG with Transparency

```php
// Transparency is preserved
$processor->convertToWebP('logo.png', 'logo.webp', 90);
```

### Batch Conversion

```php
$images = glob('photos/*.{jpg,png}', GLOB_BRACE);

foreach ($images as $image) {
    $filename = pathinfo($image, PATHINFO_FILENAME);
    $processor->convertToWebP($image, "webp/{$filename}.webp", 85);
}
```

### With Size Comparison

```php
$inputPath = 'photo.jpg';
$outputPath = 'photo.webp';

$originalSize = filesize($inputPath);

$processor->convertToWebP($inputPath, $outputPath, 85);

$webpSize = filesize($outputPath);
$savings = round((1 - $webpSize / $originalSize) * 100, 2);

echo "Reduced by {$savings}%\n";
```

## Quality Guidelines

### Recommended Settings

| Use Case | Quality | File Size | Visual Quality |
|----------|---------|-----------|----------------|
| High-end photography | 90-95 | Medium | Excellent |
| Standard web images | 80-85 | Small | Very good |
| Thumbnails | 70-80 | Very small | Good |
| Icons/previews | 60-70 | Minimal | Acceptable |

### Quality Comparison

```php
// Test different qualities
$qualities = [95, 85, 75, 65];

foreach ($qualities as $quality) {
    $processor->convertToWebP(
        'photo.jpg',
        "test-{$quality}.webp",
        $quality
    );
}
```

## Format Support

### Input Formats

| Format | Support | Notes |
|--------|---------|-------|
| JPEG | ✅ Full | Best compression gains |
| PNG | ✅ Full | Transparency preserved |
| GIF | ✅ Partial | First frame only |

### Output Format

Always outputs WebP format, regardless of input.

## Browser Support

WebP is supported by:
- Chrome 23+
- Firefox 65+
- Edge 18+
- Safari 14+ (macOS Big Sur)
- Opera 12.1+

### Fallback Strategy

Always provide fallback images:

```php
// Create WebP version
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);

// Keep JPEG fallback
copy('photo.jpg', 'photo-fallback.jpg');
// Or compress it
$processor->compressToJpg('photo.jpg', 'photo-fallback.jpg', 150);
```

Use in HTML:

```html
<picture>
    <source srcset="photo.webp" type="image/webp">
    <img src="photo-fallback.jpg" alt="Photo">
</picture>
```

## Performance

### Typical Savings

Compared to original formats:

| Original | WebP Quality 85 | Savings |
|----------|----------------|---------|
| JPEG 100KB | ~70KB | 30% |
| PNG 200KB | ~80KB | 60% |
| GIF 150KB | ~50KB | 67% |

### Processing Speed

Typical conversion times:

| Image Size | Time |
|------------|------|
| 4000x3000 | ~0.3s |
| 1920x1080 | ~0.1s |
| 800x600 | ~0.05s |

## Best Practices

### 1. Use Appropriate Quality

```php
// ✅ Good: Balanced quality
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);

// ❌ Bad: Unnecessarily high
$processor->convertToWebP('photo.jpg', 'photo.webp', 100);

// ❌ Bad: Too low quality
$processor->convertToWebP('photo.jpg', 'photo.webp', 30);
```

### 2. Always Use .webp Extension

```php
// ✅ Good: Clear extension
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);

// ⚠️ Confusing: Wrong extension
$processor->convertToWebP('photo.jpg', 'photo.jpg', 85);
```

### 3. Test Quality Levels

```php
// Find the sweet spot for your images
foreach ([95, 85, 75, 65] as $quality) {
    $processor->convertToWebP('photo.jpg', "test-{$quality}.webp", $quality);
    // Compare visually
}
```

### 4. Provide Fallbacks

```php
// Create both formats
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);
$processor->compressToJpg('photo.jpg', 'photo.jpg', 150);
```

## Common Issues

### Issue: WebP not supported

**Cause:** GD library not compiled with WebP support

**Solution:** Check and install WebP support:

```bash
# Check if WebP is supported
php -r "echo function_exists('imagewebp') ? 'Supported' : 'Not supported';"

# Ubuntu/Debian
sudo apt-get install libwebp-dev
sudo apt-get install php-gd

# Rebuild PHP with WebP support if needed
```

### Issue: Quality too low

**Cause:** Quality parameter too low

**Solution:** Increase quality:

```php
// Increase from 60 to 85
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);
```

### Issue: File size not reduced

**Cause:** Input already optimized or quality too high

**Solution:** Lower quality or check input:

```php
// Lower quality
$processor->convertToWebP('photo.jpg', 'photo.webp', 75);

// Check original size
echo filesize('photo.jpg') / 1024 . "KB\n";
```

## Error Handling

Handle conversion errors:

```php
<?php

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

try {
    // Check WebP support
    if (!function_exists('imagewebp')) {
        throw new Exception("WebP not supported");
    }
    
    // Validate input
    if (!file_exists('photo.jpg')) {
        throw new Exception("Input file not found");
    }
    
    // Convert
    $processor->convertToWebP('photo.jpg', 'photo.webp', 85);
    
    echo "✅ Conversion successful!";
    
} catch (Exception $e) {
    error_log("WebP error: " . $e->getMessage());
    echo "❌ Conversion failed.";
}
```

## Combining with Other Operations

### Resize and Convert

```php
// Resize first
$processor->resizeImage('large.jpg', 'temp.jpg', 1920, 1080);

// Convert to WebP
$processor->convertToWebP('temp.jpg', 'final.webp', 85);

// Clean up
unlink('temp.jpg');
```

### Watermark and Convert

```php
// Add watermark
$processor->addWatermark('photo.jpg', 'temp.jpg', 'logo.png', 'bottom-right', 10);

// Convert to WebP
$processor->convertToWebP('temp.jpg', 'final.webp', 85);

// Clean up
unlink('temp.jpg');
```

### Complete Workflow

```php
// Resize, watermark, and convert
$processor->resizeImage('original.jpg', 'temp1.jpg', 1920, 1080);
$processor->addWatermark('temp1.jpg', 'temp2.jpg', 'logo.png', 'bottom-right', 10);
$processor->convertToWebP('temp2.jpg', 'final.webp', 85);

// Clean up
unlink('temp1.jpg');
unlink('temp2.jpg');
```

## Related Methods

- [`compressToJpg()`](/reference/compress-to-jpg/) - JPEG compression
- [`resizeImage()`](/reference/resize-image/) - Resize before converting

## See Also

- [WebP Conversion Guide](/guides/webp-conversion/) - Detailed guide
- [Combined Operations](/guides/combined-operations/) - Efficient workflows
- [ImageProcessor Class](/reference/imageprocessor/) - Full class reference
