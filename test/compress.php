<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// maximum file size allowed. This will always set if `quality` reached to 0.
$maxSize = 200;

// Maximum image quality of the original. This will be a 0-100 scale. Image will be comprress to a below this value in order to full fill the `$maxSize` parameter.
$quality = 60;

// File name
$fileName = 'compress.jpg';

// Read file path.
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path.
$outputPath = __DIR__ . '/images/output/' . $fileName;

// Compress the image to JPG and save to the output folder.
$processor->compressToJpg($filePath, $outputPath, $maxSize, $quality);