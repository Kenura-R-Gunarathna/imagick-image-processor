<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Maximum width of the image.
$maxWidth = 620;

// Maximum height of the image.
$maxHeight = 466;

// File name.
$fileName = 'resize.jpg';

// Read file path.
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path.
$outputPath = __DIR__ . '/images/output/' . $fileName;

// Resize the image and save to the output folder.
$processor->resizeImage($filePath, $outputPath, $maxWidth, $maxHeight);