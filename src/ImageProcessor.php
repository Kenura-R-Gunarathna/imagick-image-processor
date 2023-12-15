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

    public function addWatermark($inputImagePath, $outputImagePath, $watermarkImagePath, $position = 'center', $scalePercent = 10)
    {
        $image = $this->openImage($inputImagePath);
        $watermark = $this->openImage($watermarkImagePath);

        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        $watermarkWidth = imagesx($watermark);
        $watermarkHeight = imagesy($watermark);

        // Calculate the diagonal length of the image
        $imageDiagonal = sqrt($imageWidth * $imageWidth + $imageHeight * $imageHeight);

        // Calculate the scaled width and height of the watermark based on the diagonal length
        $scaledWidth = ($scalePercent / 100) * $imageDiagonal;
        $scaledHeight = $scaledWidth * ($watermarkHeight / $watermarkWidth);

        // Calculate watermark position
        switch ($position) {
            case 'top':
                $x = ($imageWidth - $scaledWidth) / 2;
                $y = 0;
                break;
            case 'bottom':
                $x = ($imageWidth - $scaledWidth) / 2;
                $y = $imageHeight - $scaledHeight;
                break;
            case 'left':
                $x = 0;
                $y = ($imageHeight - $scaledHeight) / 2;
                break;
            case 'right':
                $x = $imageWidth - $scaledWidth;
                $y = ($imageHeight - $scaledHeight) / 2;
                break;
            case 'top-left':
                $x = 0;
                $y = 0;
                break;
            case 'top-right':
                $x = $imageWidth - $scaledWidth;
                $y = 0;
                break;
            case 'bottom-left':
                $x = 0;
                $y = $imageHeight - $scaledHeight;
                break;
            case 'bottom-right':
                $x = $imageWidth - $scaledWidth;
                $y = $imageHeight - $scaledHeight;
                break;
            case 'center':
            default:
                $x = ($imageWidth - $scaledWidth) / 2;
                $y = ($imageHeight - $scaledHeight) / 2;
                break;
        }

        // Merge the images
        imagecopyresampled($image, $watermark, $x, $y, 0, 0, $scaledWidth, $scaledHeight, imagesx($watermark), imagesy($watermark));

        // Save the image with watermark
        imagejpeg($image, $outputImagePath);

        // Destroy resources
        imagedestroy($image);
        imagedestroy($watermark);
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