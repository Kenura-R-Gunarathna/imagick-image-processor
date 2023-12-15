<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$maxSize = 200;

$quality = 60;

// File name
$fileName = 'compress.jpg';

// Read file path
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path
$compressedOutputPath = __DIR__ . '/images/output/' . $fileName;

// Compress the image to JPG and save to the output folder
$processor->compressToJpg($filePath, $compressedOutputPath, $maxSize, $quality);