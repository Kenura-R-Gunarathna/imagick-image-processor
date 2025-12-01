---
title: addOpacity()
description: API reference for the addOpacity() method.
---

Adjusts the opacity/transparency of an image. Output is always PNG to preserve transparency.

## Signature

```php
public function addOpacity(
    string $inputImagePath,
    string $outputImagePath,
    int $opacityPercent
): void
```

## Parameters

### `$inputImagePath` (string, required)
Path to the input image file. Supports JPEG, PNG, and GIF.

### `$outputImagePath` (string, required)
Path where the output PNG will be saved. Always outputs PNG format.

### `$opacityPercent` (int, required)
Opacity level (0-100).

- `0` - Fully transparent (invisible)
- `50` - Semi-transparent
- `100` - Fully opaque (no transparency)

Values outside 0-100 are automatically clamped.

## Return Value

Returns `void`. Creates the output PNG file.

## Behavior

### Opacity Calculation

The method applies the opacity to all pixels in the image:
- Creates a transparent canvas
- Copies the original image with specified opacity
- Saves as PNG to preserve transparency

### Output Format

Always outputs PNG, regardless of input format:
- JPEG → PNG
- PNG → PNG
- GIF → PNG

:::caution
JPEG doesn't support transparency, so output is always PNG even if you specify a `.jpg` extension.
:::

## Examples

### Basic Usage

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Make image 50% transparent
$processor->addOpacity('image.jpg', 'transparent.png', 50);
```

### Create Watermark

```php
// Create semi-transparent logo for watermarking
$processor->addOpacity('logo.png', 'watermark.png', 40);

// Use as watermark
$processor->addWatermark(
    'photo.jpg',
    'watermarked.jpg',
    'watermark.png',
    'bottom-right',
    10
);
```

### Different Opacity Levels

```php
// Very transparent
$processor->addOpacity('image.jpg', 'very-transparent.png', 20);

// Semi-transparent
$processor->addOpacity('image.jpg', 'semi-transparent.png', 50);

// Slightly transparent
$processor->addOpacity('image.jpg', 'slightly-transparent.png', 80);

// Fully opaque (no change)
$processor->addOpacity('image.jpg', 'opaque.png', 100);
```

### Batch Processing

```php
$images = glob('images/*.jpg');

foreach ($images as $image) {
    $filename = pathinfo($image, PATHINFO_FILENAME);
    $processor->addOpacity($image, "transparent/{$filename}.png", 60);
}
```

## Opacity Scale

| Value | Description | Use Case |
|-------|-------------|----------|
| 0-20 | Very transparent | Ghost effects, subtle overlays |
| 20-40 | Mostly transparent | Watermarks, background patterns |
| 40-60 | Semi-transparent | Overlays, fade effects |
| 60-80 | Slightly transparent | Subtle effects |
| 80-100 | Nearly/fully opaque | Minimal transparency |

## Best Practices

### 1. Use PNG Extension

```php
// ✅ Good: Clear that output is PNG
$processor->addOpacity('image.jpg', 'output.png', 50);

// ⚠️ Confusing: Output is still PNG despite .jpg extension
$processor->addOpacity('image.jpg', 'output.jpg', 50);
```

### 2. Validate Opacity Range

```php
// Values are automatically clamped, but validate for clarity
$opacity = max(0, min(100, $userInput));

$processor->addOpacity('image.jpg', 'output.png', $opacity);
```

### 3. Consider File Size

```php
// PNG files can be larger than JPEG
// Original JPEG: 500KB
$processor->addOpacity('photo.jpg', 'transparent.png', 50);
// Output PNG might be: 800KB+
```

## Common Use Cases

### 1. Watermark Creation

```php
// Create transparent watermark
$processor->addOpacity('logo.png', 'watermark.png', 50);
```

### 2. Overlay Effects

```php
// Create semi-transparent overlay
$processor->addOpacity('texture.jpg', 'overlay.png', 30);
```

### 3. Fade Effects

```php
// Create faded version
$processor->addOpacity('image.jpg', 'faded.png', 60);
```

## Common Issues

### Issue: Output file is JPEG, not PNG

**Cause:** You specified `.jpg` extension

**Solution:** Output is always PNG. Use `.png` extension:

```php
// Even with .jpg extension, output is PNG
$processor->addOpacity('input.jpg', 'output.jpg', 50);

// Better: use .png extension
$processor->addOpacity('input.jpg', 'output.png', 50);
```

### Issue: File size increased

**Cause:** PNG files are often larger than JPEG

**Solution:** This is expected when converting JPEG to PNG with transparency

### Issue: No visible change at 100% opacity

**Cause:** 100% opacity means fully opaque (no transparency)

**Solution:** Use lower values for transparency:

```php
// No change
$processor->addOpacity('image.jpg', 'output.png', 100);

// Transparent
$processor->addOpacity('image.jpg', 'output.png', 50);
```

## Related Methods

- [`addWatermark()`](/reference/add-watermark/) - Use transparent images as watermarks
- [`resizeImage()`](/reference/resize-image/) - Resize before adjusting opacity

## See Also

- [Opacity Guide](/guides/opacity/) - Detailed guide with examples
- [Watermarks Guide](/guides/watermarks/) - Create transparent watermarks
- [ImageProcessor Class](/reference/imageprocessor/) - Full class reference
