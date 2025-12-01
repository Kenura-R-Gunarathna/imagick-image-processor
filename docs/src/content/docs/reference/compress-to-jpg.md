---
title: compressToJpg()
description: API reference for the compressToJpg() method.
---

Compresses an image to a target file size by iteratively adjusting quality.

## Signature

```php
public function compressToJpg(
    string $inputImagePath,
    string $outputImagePath,
    int $targetFileSize = 100,
    int $quality = 80
): void
```

## Parameters

### `$inputImagePath` (string, required)
Path to the input image file. Supports JPEG, PNG, and GIF formats.

### `$outputImagePath` (string, required)
Path where the compressed JPEG will be saved. Always outputs JPEG format.

### `$targetFileSize` (int, optional)
Target file size in kilobytes. Default: `100`

- Must be a positive integer
- Recommended: 20-500 KB
- Method will get as close as possible without exceeding

### `$quality` (int, optional)
Starting quality level (0-100). Default: `80`

- Higher values = better quality, larger file
- Lower values = worse quality, smaller file
- Method reduces quality iteratively to reach target size

## Return Value

Returns `void`. Creates the output JPEG file.

## Behavior

### Compression Algorithm

1. Copies input to output path
2. Checks current file size
3. If larger than target:
   - Compresses with current quality
   - Reduces quality by 10
   - Repeats until target reached or quality < 10
4. Stops when file size ≤ target

### Output Format

Always outputs JPEG, regardless of input format:
- PNG → JPEG (transparency becomes white)
- GIF → JPEG (first frame only)
- JPEG → JPEG (recompressed)

## Examples

### Basic Compression

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Compress to ~100KB
$processor->compressToJpg('large.jpg', 'compressed.jpg', 100);
```

### Custom Quality

```php
// Start with high quality (90)
$processor->compressToJpg('photo.jpg', 'compressed.jpg', 200, 90);

// Start with lower quality for faster processing
$processor->compressToJpg('screenshot.jpg', 'compressed.jpg', 50, 70);
```

### Batch Compression

```php
$images = glob('photos/*.jpg');

foreach ($images as $image) {
    $filename = basename($image);
    $processor->compressToJpg($image, "compressed/{$filename}", 150);
}
```

### With Size Reporting

```php
$inputSize = filesize('input.jpg');

$processor->compressToJpg('input.jpg', 'output.jpg', 100);

$outputSize = filesize('output.jpg');
$savings = round((1 - $outputSize / $inputSize) * 100, 2);

echo "Reduced by {$savings}%\n";
```

## Best Practices

### 1. Set Realistic Targets

```php
$originalSize = filesize('photo.jpg') / 1024; // KB

// Don't set target larger than original
$targetSize = min(100, $originalSize * 0.8);

$processor->compressToJpg('photo.jpg', 'compressed.jpg', $targetSize);
```

### 2. Resize Before Compressing

```php
// More efficient: resize first, then compress
$processor->resizeImage('huge.jpg', 'temp.jpg', 1200, 800);
$processor->compressToJpg('temp.jpg', 'final.jpg', 150);
unlink('temp.jpg');
```

### 3. Choose Appropriate Quality

| Use Case | Target Size | Starting Quality |
|----------|-------------|------------------|
| High-quality photos | 200-500 KB | 90 |
| Standard web images | 100-200 KB | 80 |
| Thumbnails | 30-100 KB | 70 |
| Icons/previews | <30 KB | 60 |

## Common Issues

### Issue: File larger than target

**Cause:** Quality can't go below 10

**Solution:** Resize the image first to reduce dimensions

### Issue: Poor quality output

**Cause:** Target size too small for image dimensions

**Solution:** Increase target size or resize image first

### Issue: Transparency lost

**Cause:** JPEG doesn't support transparency

**Solution:** Use PNG for images requiring transparency

## Related Methods

- [`resizeImage()`](/reference/resize-image/) - Resize before compressing
- [Combined Operations](/guides/combined-operations/) - Efficient workflows

## See Also

- [Compressing Images Guide](/guides/compressing/) - Detailed guide
- [ImageProcessor Class](/reference/imageprocessor/) - Full class reference
