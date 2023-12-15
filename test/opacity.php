<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Opacity of the image.
$opacity = 620;

// File name.
$fileName = 'opacity.jpg';

// Read file path.
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path.
$outputPath = __DIR__ . '/images/output/' . $fileName;

$imageProcessor->addOpacity($filePath, $outputPath, $opacity);
