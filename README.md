# Imagick Image Processor

[![Tests](https://github.com/Kenura-R-Gunarathna/imagick-image-processor/workflows/Tests/badge.svg)](https://github.com/Kenura-R-Gunarathna/imagick-image-processor/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A powerful and easy-to-use PHP library for image processing using the GD extension. Transform, compress, watermark, and optimize images with just a few lines of code.

## ✨ Features

- 🖼️ **Image Resizing** - Maintain aspect ratios automatically
- 🗜️ **Smart Compression** - Compress to target file sizes
- 🏷️ **Watermarking** - 9 preset positions with diagonal-based scaling
- 🎨 **Opacity Control** - Add transparency to images
- 🚀 **WebP Conversion** - Modern format with superior compression
- ⚡ **Combined Operations** - Efficient multi-step workflows

## 📋 Requirements

- PHP 7.4 or higher
- GD extension (usually included with PHP)
- `ext-imagick` version 3.7 or higher

## 📦 Installation

Install via Composer:

```bash
composer require kenura/imagick
```

## 🚀 Quick Start

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Resize an image
$processor->resizeImage('input.jpg', 'output.jpg', 800, 600);

// Compress to ~100KB
$processor->compressToJpg('large.jpg', 'compressed.jpg', 100);

// Add watermark
$processor->addWatermark('photo.jpg', 'watermarked.jpg', 'logo.png', 'bottom-right', 10);

// Convert to WebP
$processor->convertToWebP('photo.jpg', 'photo.webp', 85);
```

## 📖 Methods

### Core Methods

#### `resizeImage()`
Resize images while maintaining aspect ratio.

```php
$processor->resizeImage($inputPath, $outputPath, $width, $height);
```

#### `compressToJpg()`
Compress images to a target file size.

```php
$processor->compressToJpg($inputPath, $outputPath, $targetSizeKB, $quality = 80);
```

#### `addWatermark()`
Add watermarks with flexible positioning.

```php
$processor->addWatermark($inputPath, $outputPath, $watermarkPath, $position = 'center', $scale = 10);
```

**Positions:** `center`, `top`, `bottom`, `left`, `right`, `top-left`, `top-right`, `bottom-left`, `bottom-right`

#### `addOpacity()`
Adjust image transparency (output as PNG).

```php
$processor->addOpacity($inputPath, $outputPath, $opacityPercent);
```

#### `convertToWebP()`
Convert images to modern WebP format.

```php
$processor->convertToWebP($inputPath, $outputPath, $quality = 80);
```

### Combined Operations

#### `resizeAndCompress()`
Resize and compress in one step.

```php
$processor->resizeAndCompress($inputPath, $outputPath, $width, $height, $targetSizeKB);
```

#### `resizeWatermarkAndCompress()`
Complete processing pipeline.

```php
$processor->resizeWatermarkAndCompress(
    $inputPath, 
    $outputPath, 
    $width, 
    $height, 
    $watermarkPath, 
    $position, 
    $scale, 
    $scale, 
    $targetSizeKB
);
```

## 🧪 Testing

This library includes both manual example scripts and automated tests.

### Manual Examples

Run example scripts to see the library in action:

```bash
# Resize example
php test/resize.php

# Compression example
php test/compress.php

# Watermark example
php test/watermark.php

# WebP conversion example
php test/webp.php

# Opacity example
php test/opacity.php
```

### Automated Tests

Run the full PHPUnit test suite:

```bash
# Install dev dependencies
composer install

# Run tests
composer test

# Run tests with coverage
composer test:coverage
```

The automated tests run on every push via GitHub Actions, testing across PHP 7.4, 8.0, 8.1, 8.2, and 8.3.

## 📚 Documentation

For detailed guides and API reference, visit the [full documentation](https://kenura-r-gunarathna.github.io/imagick-image-processor/).

- [Installation Guide](https://kenura-r-gunarathna.github.io/imagick-image-processor/getting-started/installation/)
- [Quick Start](https://kenura-r-gunarathna.github.io/imagick-image-processor/getting-started/quick-start/)
- [Resizing Images](https://kenura-r-gunarathna.github.io/imagick-image-processor/guides/resizing/)
- [Compressing Images](https://kenura-r-gunarathna.github.io/imagick-image-processor/guides/compressing/)
- [Adding Watermarks](https://kenura-r-gunarathna.github.io/imagick-image-processor/guides/watermarks/)
- [WebP Conversion](https://kenura-r-gunarathna.github.io/imagick-image-processor/guides/webp-conversion/)
- [API Reference](https://kenura-r-gunarathna.github.io/imagick-image-processor/reference/imageprocessor/)

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**Kenura R. Gunarathna**
- Email: kenuragunarathna@gmail.com
- GitHub: [@Kenura-R-Gunarathna](https://github.com/Kenura-R-Gunarathna)

## 🙏 Acknowledgments

Thanks to everyone using this library! Your feedback and contributions help make it better.

