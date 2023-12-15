<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$maxSize = 200;

$quality = 60;

$maxWidth = 620;

$maxHeight = 466;

// File name
$fileName = 'resize-compress.jpg';

// Read file path
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path
$outputPath = __DIR__ . '/images/output/' . $fileName;

// Compress the image to JPG and save to the output folder
$processor->compressToJpg($filePath, $outputPath, $maxSize, $quality);

// Resize the image and save to the output folder
$processor->resizeImage($outputPath, $outputPath, $maxWidth, $maxHeight);