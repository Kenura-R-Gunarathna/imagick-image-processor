<?php

namespace Kenura\Imagick;

class ImageProcessor
{
    public function resizeImage($inputImagePath, $outputImagePath, $width, $height)
    {
        list($origWidth, $origHeight) = getimagesize($inputImagePath);
        $aspectRatio = $origWidth / $origHeight;

        if ($width / $height > $aspectRatio) {
            $width = $height * $aspectRatio;
        } else {
            $height = $width / $aspectRatio;
        }

        $image = $this->openImage($inputImagePath);
        $resizedImage = imagescale($image, $width, $height);

        // Save the resized image
        imagejpeg($resizedImage, $outputImagePath);

        imagedestroy($image);
        imagedestroy($resizedImage);
    }

    public function compressToJpg($inputImagePath, $outputImagePath, $targetFileSize = 100, $quality = 80)
    {
        // Copy the imagez
        copy($inputImagePath, $outputImagePath);

        $currentFileSize = filesize($outputImagePath);
        
        while ($currentFileSize > ($targetFileSize * 1024) && $quality > 10) {

            $image = $this->openImage($outputImagePath);

            // Save the compressed image
            imagejpeg($image, $outputImagePath, $quality);

            imagedestroy($image);

            // Check the file size
            $currentFileSize = filesize($outputImagePath);

            // Adjust quality for the next iteration
            $quality -= 10;

            // Ensure the quality is within valid range (0-100)
            $quality = max(min($quality, 100), 0);
        }
        
    }

    private function openImage($imagePath)
    {
        $imageInfo = getimagesize($imagePath);
        $imageType = $imageInfo[2];

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($imagePath);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($imagePath);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($imagePath);
            // Add more cases for other image types as needed
            default:
                trigger_error("Unsupported image type: {$imageType}", E_USER_WARNING);
                throw new \Exception('Unsupported image type');
        }
    }
}