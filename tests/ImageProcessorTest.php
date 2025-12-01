<?php

namespace Kenura\Imagick\Tests;

use Kenura\Imagick\ImageProcessor;
use PHPUnit\Framework\TestCase;

class ImageProcessorTest extends TestCase
{
    private ImageProcessor $processor;
    private string $testImagePath;
    private string $testWatermarkPath;
    private string $outputDir;

    protected function setUp(): void
    {
        $this->processor = new ImageProcessor();
        $this->testImagePath = __DIR__ . '/fixtures/test-image.jpg';
        $this->testWatermarkPath = __DIR__ . '/fixtures/watermark.png';
        $this->outputDir = __DIR__ . '/output';

        // Create output directory if it doesn't exist
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }

        // Create test image if it doesn't exist
        if (!file_exists($this->testImagePath)) {
            $this->createTestImage($this->testImagePath, 800, 600);
        }

        // Create test watermark if it doesn't exist
        if (!file_exists($this->testWatermarkPath)) {
            $this->createTestWatermark($this->testWatermarkPath, 100, 100);
        }
    }

    protected function tearDown(): void
    {
        // Clean up output files
        if (is_dir($this->outputDir)) {
            $files = glob($this->outputDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }

    private function createTestImage(string $path, int $width, int $height): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $image = imagecreatetruecolor($width, $height);
        $blue = imagecolorallocate($image, 0, 0, 255);
        imagefill($image, 0, 0, $blue);
        imagejpeg($image, $path, 90);
        imagedestroy($image);
    }

    private function createTestWatermark(string $path, int $width, int $height): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $image = imagecreatetruecolor($width, $height);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 10, 10, 90, 90, $white);
        imagesavealpha($image, true);
        imagepng($image, $path);
        imagedestroy($image);
    }

    public function testResizeImage(): void
    {
        $outputPath = $this->outputDir . '/resized.jpg';
        
        $this->processor->resizeImage(
            $this->testImagePath,
            $outputPath,
            400,
            300
        );

        $this->assertFileExists($outputPath);
        
        list($width, $height) = getimagesize($outputPath);
        $this->assertLessThanOrEqual(400, $width);
        $this->assertLessThanOrEqual(300, $height);
    }

    public function testCompressToJpg(): void
    {
        $outputPath = $this->outputDir . '/compressed.jpg';
        $targetSize = 50; // 50KB

        $this->processor->compressToJpg(
            $this->testImagePath,
            $outputPath,
            $targetSize
        );

        $this->assertFileExists($outputPath);
        
        $fileSize = filesize($outputPath) / 1024; // Convert to KB
        $this->assertLessThanOrEqual($targetSize * 1.1, $fileSize); // Allow 10% margin
    }

    public function testAddWatermark(): void
    {
        $outputPath = $this->outputDir . '/watermarked.jpg';

        $this->processor->addWatermark(
            $this->testImagePath,
            $outputPath,
            $this->testWatermarkPath,
            'bottom-right',
            10
        );

        $this->assertFileExists($outputPath);
        
        // Verify output is a valid image
        $imageInfo = getimagesize($outputPath);
        $this->assertNotFalse($imageInfo);
    }

    public function testAddOpacity(): void
    {
        $outputPath = $this->outputDir . '/opacity.png';

        $this->processor->addOpacity(
            $this->testImagePath,
            $outputPath,
            50
        );

        $this->assertFileExists($outputPath);
        
        // Verify output is PNG
        $imageInfo = getimagesize($outputPath);
        $this->assertEquals(IMAGETYPE_PNG, $imageInfo[2]);
    }

    public function testConvertToWebP(): void
    {
        // Check if WebP is supported
        if (!function_exists('imagewebp')) {
            $this->markTestSkipped('WebP support not available');
        }

        $outputPath = $this->outputDir . '/converted.webp';

        $this->processor->convertToWebP(
            $this->testImagePath,
            $outputPath,
            85
        );

        $this->assertFileExists($outputPath);
        
        // Verify output is WebP
        $imageInfo = getimagesize($outputPath);
        $this->assertEquals(IMAGETYPE_WEBP, $imageInfo[2]);
        
        // Verify file size is smaller than original
        $originalSize = filesize($this->testImagePath);
        $webpSize = filesize($outputPath);
        $this->assertLessThan($originalSize, $webpSize);
    }

    public function testWatermarkPositions(): void
    {
        $positions = [
            'center', 'top', 'bottom', 'left', 'right',
            'top-left', 'top-right', 'bottom-left', 'bottom-right'
        ];

        foreach ($positions as $position) {
            $outputPath = $this->outputDir . "/watermark-{$position}.jpg";
            
            $this->processor->addWatermark(
                $this->testImagePath,
                $outputPath,
                $this->testWatermarkPath,
                $position,
                10
            );

            $this->assertFileExists($outputPath, "Failed to create watermark at position: {$position}");
        }
    }

    public function testOpacityRange(): void
    {
        // Test that opacity is clamped to 0-100 range
        $outputPath1 = $this->outputDir . '/opacity-negative.png';
        $outputPath2 = $this->outputDir . '/opacity-over.png';

        // Should not throw exception, should clamp to 0
        $this->processor->addOpacity($this->testImagePath, $outputPath1, -10);
        $this->assertFileExists($outputPath1);

        // Should not throw exception, should clamp to 100
        $this->processor->addOpacity($this->testImagePath, $outputPath2, 150);
        $this->assertFileExists($outputPath2);
    }

    public function testInvalidInputFile(): void
    {
        $this->expectException(\Exception::class);
        
        $this->processor->resizeImage(
            'non-existent-file.jpg',
            $this->outputDir . '/output.jpg',
            400,
            300
        );
    }
}
