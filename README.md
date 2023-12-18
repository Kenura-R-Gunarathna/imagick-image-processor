# Imagick Image Processor

This library, created with focuses on simple methods for image processing using the `Imagick` PHP extension.

## Requirements

- PHP extension `ext-imagick` version > 3.7

## Installation

To install Imagick Image Processor using Composer, run the following command:

```bash
composer require kenura/imagick
```

## Methods

### Resize

The `resizeImage` method resizes an image with the specified width and height.

```php
public function resizeImage($inputImagePath, $outputImagePath, $width, $height);
```

### Compress

The `compressToJpg` method compresses an image to JPEG format with a specified quality.

```php
public function compressToJpg($inputImagePath, $outputImagePath, $quality = 80);
```

### Watermark

The `addWatermark` method adds a watermark to an image with various positioning options, preserving the width*height ratio of the watermark.

```php
public function addWatermark($inputImagePath, $outputImagePath, $watermarkImagePath, $position = 'center', $widthPercent = 10, $heightPercent = 10);
```

### Opacity

The `addOpacity` method makes an image transparent with a specified opacity percentage and saves it as PNG.

```php
public function addOpacity($inputImagePath, $outputImagePath, $opacityPercent);
```

### Resize-Compress

The `resizeAndCompress` method combines image resizing and compression.

```php
public function resizeAndCompress($inputImagePath, $outputImagePath, $width, $height, $quality = 80);
```

### Resize-Watermark-Compression

The `resizeWatermarkAndCompress` method combines image resizing, watermarking, and compression.

```php
public function resizeWatermarkAndCompress($inputImagePath, $outputImagePath, $width, $height, $watermarkImagePath, $position = 'center', $widthPercent = 10, $heightPercent = 10, $quality = 80);
```

## Usage

```php
use YourNamespace\ImageProcessor;

// Create an instance of the ImageProcessor
$imageProcessor = new ImageProcessor();

// Use the methods based on your requirements
$imageProcessor->resizeImage($inputImagePath, $outputImagePath, $width, $height);
$imageProcessor->compressToJpg($inputImagePath, $outputImagePath, $quality);
$imageProcessor->addWatermark($inputImagePath, $outputImagePath, $watermarkImagePath, $position, $widthPercent, $heightPercent);
$imageProcessor->addOpacity($inputImagePath, $outputImagePath, $opacityPercent);
$imageProcessor->resizeAndCompress($inputImagePath, $outputImagePath, $width, $height, $quality);
$imageProcessor->resizeWatermarkAndCompress($inputImagePath, $outputImagePath, $width, $height, $watermarkImagePath, $position, $widthPercent, $heightPercent, $quality);
```

## Acknowledgment

Thanks to @kenuragunarathna@gmail.com for using this library!

Feel free to contribute or report issues.
