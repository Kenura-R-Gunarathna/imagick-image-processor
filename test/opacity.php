<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Opacity of the image as a percentage.
$opacity = 50;

// File name. This should support the image opacity.
$fileName = 'opacity.png';

// Read file path.
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path. Here output image allways should be an png image to preserve the opacity.
$outputPath = __DIR__ . '/images/output/' . $fileName;

// Opaq the image
$processor->addOpacity($filePath, $outputPath, $opacity);
