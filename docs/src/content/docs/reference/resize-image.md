---
title: resizeImage()
description: API reference for the resizeImage() method.
---

Resizes an image while automatically maintaining its aspect ratio.

## Signature

```php
public function resizeImage(
    string $inputImagePath,
    string $outputImagePath,
    int $width,
    int $height
): void
```

## Parameters

### `$inputImagePath` (string, required)

Path to the input image file.

- Must be a valid file path
- File must exist and be readable
- Supported formats: JPEG, PNG, GIF

**Example:**
```php
'path/to/input.jpg'
'/var/www/uploads/photo.png'
'../images/picture.gif'
```

### `$outputImagePath` (string, required)

Path where the resized image will be saved.

- Directory must exist or be creatable
- Must have write permissions
- Output format matches input format

**Example:**
```php
'path/to/output.jpg'
'/var/www/processed/resized.png'
'../images/thumbnail.gif'
```

### `$width` (int, required)

Target width in pixels.

- Must be a positive integer
- Actual width may be smaller to maintain aspect ratio
- Recommended: 100-4000 pixels

**Example:**
```php
800   // 800 pixels wide
1920  // Full HD width
300   // Thumbnail width
```

### `$height` (int, required)

Target height in pixels.

- Must be a positive integer
- Actual height may be smaller to maintain aspect ratio
- Recommended: 100-4000 pixels

**Example:**
```php
600   // 600 pixels tall
1080  // Full HD height
300   // Thumbnail height
```

## Return Value

Returns `void`. The method doesn't return a value but creates the output file.

## Exceptions

May throw `Exception` in the following cases:

- Input file doesn't exist
- Input file is not a valid image
- Unsupported image format
- Output directory is not writable
- Insufficient memory for processing
- Image processing fails

## Behavior

### Aspect Ratio Preservation

The method automatically calculates the best dimensions to maintain the original aspect ratio:

```php
// Original: 1920x1080 (16:9)
// Requested: 800x600
// Result: 800x450 (maintains 16:9)

$processor->resizeImage('wide.jpg', 'resized.jpg', 800, 600);
```

### Algorithm

1. Gets original image dimensions
2. Calculates aspect ratio
3. Determines which dimension to constrain
4. Calculates new dimensions maintaining ratio
5. Performs high-quality resize using `imagescale()`
6. Saves to output path

### Quality

Uses `imagescale()` which provides high-quality resampling:
- Bicubic interpolation
- Smooth edges
- Minimal artifacts

## Examples

### Basic Resize

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$processor->resizeImage('photo.jpg', 'resized.jpg', 800, 600);
```

### Thumbnail Generation

```php
// Create 150x150 thumbnail
$processor->resizeImage('large.jpg', 'thumb.jpg', 150, 150);
```

### Web Optimization

```php
// Resize for web display
$processor->resizeImage('huge.jpg', 'web.jpg', 1200, 800);
```

### Batch Processing

```php
$images = glob('photos/*.jpg');

foreach ($images as $image) {
    $filename = basename($image);
    $processor->resizeImage($image, "resized/{$filename}", 800, 600);
}
```

### With Error Handling

```php
try {
    $processor->resizeImage('input.jpg', 'output.jpg', 800, 600);
    echo "Resize successful!";
} catch (Exception $e) {
    error_log("Resize failed: " . $e->getMessage());
    echo "Failed to resize image.";
}
```

### Conditional Resize

```php
// Only resize if larger than target
list($width, $height) = getimagesize('photo.jpg');

if ($width > 1200 || $height > 800) {
    $processor->resizeImage('photo.jpg', 'resized.jpg', 1200, 800);
} else {
    copy('photo.jpg', 'resized.jpg');
}
```

## Format Support

### Input Formats

| Format | Extension | Support |
|--------|-----------|---------|
| JPEG | `.jpg`, `.jpeg` | ✅ Full |
| PNG | `.png` | ✅ Full (transparency preserved) |
| GIF | `.gif` | ✅ First frame only |

### Output Format

Output format matches input format:
- JPEG input → JPEG output
- PNG input → PNG output
- GIF input → GIF output

## Performance

### Speed

Typical processing times (on modern hardware):

| Image Size | Resize To | Time |
|------------|-----------|------|
| 4000x3000 | 1200x900 | ~0.5s |
| 1920x1080 | 800x600 | ~0.2s |
| 800x600 | 300x225 | ~0.1s |

### Memory Usage

Approximate memory requirements:

```
Memory (MB) ≈ (Width × Height × 4) / 1,048,576
```

Example:
- 4000x3000 image ≈ 46 MB
- 1920x1080 image ≈ 8 MB
- 800x600 image ≈ 2 MB

## Best Practices

### 1. Validate Input

```php
if (!file_exists($inputPath)) {
    throw new Exception("Input file not found");
}

if (getimagesize($inputPath) === false) {
    throw new Exception("Invalid image file");
}
```

### 2. Don't Upscale

```php
list($origWidth, $origHeight) = getimagesize($inputPath);

if ($origWidth <= $targetWidth && $origHeight <= $targetHeight) {
    // Don't resize, just copy
    copy($inputPath, $outputPath);
} else {
    $processor->resizeImage($inputPath, $outputPath, $targetWidth, $targetHeight);
}
```

### 3. Use Appropriate Dimensions

```php
// ✅ Good: Reasonable dimensions
$processor->resizeImage('photo.jpg', 'web.jpg', 1200, 800);

// ❌ Bad: Unnecessarily large
$processor->resizeImage('photo.jpg', 'huge.jpg', 8000, 6000);

// ❌ Bad: Too small, quality loss
$processor->resizeImage('photo.jpg', 'tiny.jpg', 50, 50);
```

### 4. Clean Up Temporary Files

```php
$tempFile = 'temp_' . uniqid() . '.jpg';

try {
    $processor->resizeImage('input.jpg', $tempFile, 800, 600);
    // ... use $tempFile
} finally {
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
}
```

## Common Issues

### Issue: Output is smaller than expected

**Cause:** Aspect ratio preservation

**Solution:** This is expected behavior. The method ensures the image fits within your specified dimensions while maintaining aspect ratio.

```php
// Original: 1920x1080
// Requested: 800x800
// Result: 800x450 (not 800x800)
```

### Issue: Image quality loss

**Cause:** Resizing to very small dimensions

**Solution:** Use reasonable target dimensions. For thumbnails, 150-300px is usually sufficient.

### Issue: Memory errors

**Cause:** Processing very large images

**Solution:** Increase PHP memory limit or resize in steps:

```php
// php.ini
memory_limit = 256M

// Or in code
ini_set('memory_limit', '256M');
```

## Related Methods

- [`compressToJpg()`](/reference/compress-to-jpg/) - Compress after resizing
- [`addWatermark()`](/reference/add-watermark/) - Add watermark to resized image

## See Also

- [Resizing Images Guide](/guides/resizing/) - Detailed guide with examples
- [Combined Operations](/guides/combined-operations/) - Combine with other operations
- [ImageProcessor Class](/reference/imageprocessor/) - Full class reference
