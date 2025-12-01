---
title: addWatermark()
description: API reference for the addWatermark() method.
---

Adds a watermark to an image with flexible positioning and diagonal-based scaling.

## Signature

```php
public function addWatermark(
    string $inputImagePath,
    string $outputImagePath,
    string $watermarkImagePath,
    string $position = 'center',
    int $scalePercent = 10
): void
```

## Parameters

### `$inputImagePath` (string, required)
Path to the input image file.

### `$outputImagePath` (string, required)
Path where the watermarked image will be saved.

### `$watermarkImagePath` (string, required)
Path to the watermark image file. PNG with transparency recommended.

### `$position` (string, optional)
Watermark position. Default: `'center'`

**Valid values:**
- `'center'` - Center of image
- `'top'` - Top center
- `'bottom'` - Bottom center
- `'left'` - Left center
- `'right'` - Right center
- `'top-left'` - Top left corner
- `'top-right'` - Top right corner
- `'bottom-left'` - Bottom left corner
- `'bottom-right'` - Bottom right corner

### `$scalePercent` (int, optional)
Watermark size as percentage of image diagonal. Default: `10`

- Recommended: 5-30
- Smaller values = subtle watermark
- Larger values = prominent watermark

## Return Value

Returns `void`. Creates the output file with watermark.

## Behavior

### Scaling Algorithm

Watermark size is calculated based on the image's diagonal length:

```
diagonal = √(width² + height²)
watermark_width = (scalePercent / 100) × diagonal
watermark_height = watermark_width × (original_watermark_height / original_watermark_width)
```

This ensures consistent appearance across different image sizes and orientations.

### Aspect Ratio

Watermark aspect ratio is always preserved.

## Examples

### Basic Watermark

```php
use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$processor->addWatermark(
    'photo.jpg',
    'watermarked.jpg',
    'logo.png',
    'bottom-right',
    10
);
```

### All Positions

```php
$positions = [
    'center', 'top', 'bottom', 'left', 'right',
    'top-left', 'top-right', 'bottom-left', 'bottom-right'
];

foreach ($positions as $position) {
    $processor->addWatermark(
        'photo.jpg',
        "watermarked-{$position}.jpg",
        'logo.png',
        $position,
        10
    );
}
```

### Different Sizes

```php
// Small, subtle watermark
$processor->addWatermark('photo.jpg', 'small.jpg', 'logo.png', 'bottom-right', 5);

// Medium watermark
$processor->addWatermark('photo.jpg', 'medium.jpg', 'logo.png', 'bottom-right', 10);

// Large, prominent watermark
$processor->addWatermark('photo.jpg', 'large.jpg', 'logo.png', 'center', 25);
```

### Batch Watermarking

```php
$images = glob('photos/*.jpg');
$watermark = 'assets/watermark.png';

foreach ($images as $image) {
    $filename = basename($image);
    $processor->addWatermark(
        $image,
        "watermarked/{$filename}",
        $watermark,
        'bottom-right',
        10
    );
}
```

## Position Visual Guide

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

## Best Practices

### 1. Use PNG Watermarks

```php
// ✅ Good: PNG with transparency
$processor->addWatermark('photo.jpg', 'out.jpg', 'logo.png', 'bottom-right', 10);

// ⚠️ OK: JPEG watermark (no transparency)
$processor->addWatermark('photo.jpg', 'out.jpg', 'logo.jpg', 'bottom-right', 10);
```

### 2. Create Semi-Transparent Watermarks

```php
// Create transparent watermark first
$processor->addOpacity('logo.png', 'watermark.png', 50);

// Then apply
$processor->addWatermark('photo.jpg', 'out.jpg', 'watermark.png', 'bottom-right', 10);
```

### 3. Choose Appropriate Size

| Use Case | Scale % | Description |
|----------|---------|-------------|
| Copyright protection | 5-8 | Small, corner placement |
| Branding | 10-15 | Medium, visible but not intrusive |
| Preview watermark | 20-30 | Large, center placement |

## Common Issues

### Issue: Watermark too small/large

**Solution:** Adjust `$scalePercent` parameter

```php
// Too small? Increase scale
$processor->addWatermark('photo.jpg', 'out.jpg', 'logo.png', 'center', 20);

// Too large? Decrease scale
$processor->addWatermark('photo.jpg', 'out.jpg', 'logo.png', 'corner', 5);
```

### Issue: Watermark not visible

**Cause:** Watermark color blends with image

**Solution:** Use contrasting colors or add opacity

## Related Methods

- [`addOpacity()`](/reference/add-opacity/) - Create transparent watermarks
- [`resizeImage()`](/reference/resize-image/) - Resize before watermarking

## See Also

- [Watermarks Guide](/guides/watermarks/) - Detailed guide
- [ImageProcessor Class](/reference/imageprocessor/) - Full class reference
