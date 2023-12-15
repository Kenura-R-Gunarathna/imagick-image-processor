<?php

// auto loader
require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// ------------------- Compress parameters -----------------------

$maxSize = 200;

$quality = 60;

// ------------------- Resize parameters -----------------------

$maxWidth = 620;

$maxHeight = 466;

// ------------------- Watermark parameters -----------------------

// diagonal lenght of the wallpaper as a percentage of the parent.
$scalePercent = 30;

/*
    Position of the watermark. There are :- 
    
    1. top-left: Places the watermark in the top-left corner of the image.
    2. top-center: Positions the watermark at the top and centered horizontally.
    3. top-right: Aligns the watermark with the top-right corner of the image.
    4. center-left: Places the watermark in the center and aligned to the left.
    5. center: Centers the watermark both horizontally and vertically.
    6. center-right: Positions the watermark in the center and aligned to the right.
    7. bottom-left: Aligns the watermark with the bottom-left corner of the image.
    8. bottom-center: Places the watermark at the bottom and centered horizontally.
    9. bottom-right: Positions the watermark in the bottom-right corner of the image.
*/
$watermarkPosition = 'center';

// Watermark path.
$watermarkPath = __DIR__ . '/images/watermark.png';

// File name.
$fileName = 'watermark.jpg';

// ------------------- File paths -----------------------

// File name
$fileName = 'resize-watermark-compression.jpg';

// Read file path
$filePath = __DIR__ . '/images/input/' . $fileName;

// Output path
$outputPath = __DIR__ . '/images/output/' . $fileName;

// Compress the image to JPG and save to the output folder
$processor->compressToJpg($filePath, $outputPath, $maxSize, $quality);

// Add watermark.
$processor->addWatermark($outputPath, $outputPath, $watermarkPath, $watermarkPosition, $scalePercent);

// Resize the image and save to the output folder
$processor->resizeImage($outputPath, $outputPath, $maxWidth, $maxHeight);