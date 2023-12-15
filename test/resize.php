<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

$maxWidth = 620;

$maxHeight = 466;

// File name
$fileName = 'resize.jpg';

// Read file path
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path
$resizedOutputPath = __DIR__ . '/images/output/' . $fileName;

// Resize the image and save to the output folder
$processor->resizeImage($filePath, $resizedOutputPath, $maxWidth, $maxHeight);