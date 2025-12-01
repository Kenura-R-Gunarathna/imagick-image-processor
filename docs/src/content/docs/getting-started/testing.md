---
title: Testing & Contributing
description: Learn how to test the library and contribute to the project.
---

This guide covers both testing the library and contributing to its development.

## Testing

The Imagick Image Processor includes two types of tests to ensure reliability and help you understand how to use the library.

### Manual Example Scripts

The `test/` directory contains example scripts that demonstrate real-world usage. These are great for:
- Learning how to use the library
- Visual verification of results
- Quick manual testing
- Understanding method parameters

#### Running Example Scripts

```bash
# Navigate to project root
cd imagick-image-processor

# Run individual examples
php test/resize.php
php test/compress.php
php test/watermark.php
php test/opacity.php
php test/webp.php
php test/resize-compress.php
php test/resize-watermark-compression.php
```

#### Example Output

Each script processes images from `test/images/input/` and saves results to `test/images/output/`, showing you exactly what the library does.

### Automated PHPUnit Tests

The `tests/` directory contains comprehensive automated tests using PHPUnit. These tests:
- Validate all methods work correctly
- Test edge cases and error handling
- Ensure compatibility across PHP versions
- Provide code coverage reports

#### Running Automated Tests

```bash
# Install dev dependencies (first time only)
composer install

# Run all tests
composer test

# Run tests with coverage report
composer test:coverage
```

#### What Gets Tested

The test suite covers:
- ✅ Image resizing with aspect ratio preservation
- ✅ Compression to target file sizes
- ✅ All 9 watermark positions
- ✅ Opacity adjustments and clamping
- ✅ WebP conversion (if supported)
- ✅ Invalid input handling
- ✅ File format validation

### Continuous Integration

Every push and pull request automatically runs tests via GitHub Actions across multiple PHP versions:
- PHP 8.1
- PHP 8.2
- PHP 8.3

You can see test results in the [Actions tab](https://github.com/Kenura-R-Gunarathna/imagick-image-processor/actions) of the repository.

## Contributing

We welcome contributions! Here's how you can help improve the library.

### Getting Started

1. **Fork the repository**
   ```bash
   # Click "Fork" on GitHub, then clone your fork
   git clone https://github.com/YOUR-USERNAME/imagick-image-processor.git
   cd imagick-image-processor
   ```

2. **Install dependencies**
   ```bash
   composer install
   cd docs && pnpm install && cd ..
   ```

3. **Create a branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

### Development Workflow

#### 1. Make Your Changes

Edit files in the `src/` directory:
- `src/ImageProcessor.php` - Main library code

#### 2. Add Tests

For new features, add tests in `tests/ImageProcessorTest.php`:

```php
public function testYourNewFeature(): void
{
    $outputPath = $this->outputDir . '/your-feature.jpg';
    
    $this->processor->yourNewMethod('input.jpg', $outputPath);
    
    $this->assertFileExists($outputPath);
    // Add more assertions
}
```

#### 3. Add Example Script

Create a demo script in `test/`:

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Kenura\Imagick\ImageProcessor;

$processor = new ImageProcessor();

// Demonstrate your feature
$processor->yourNewMethod('input.jpg', 'output.jpg');

echo "✅ Feature demo complete!\n";
```

#### 4. Update Documentation

Add documentation in `docs/src/content/docs/`:

- **Guide**: Create `guides/your-feature.md`
- **API Reference**: Create `reference/your-method.md`
- **Update sidebar**: Edit `docs/astro.config.mjs`

#### 5. Run Tests

```bash
# Run all tests
composer test

# Test documentation locally
cd docs
pnpm dev
# Visit http://localhost:4321
```

#### 6. Commit and Push

```bash
git add .
git commit -m "Add: Your feature description"
git push origin feature/your-feature-name
```

#### 7. Create Pull Request

1. Go to your fork on GitHub
2. Click "Pull Request"
3. Describe your changes
4. Submit!

### Contribution Guidelines

#### Code Style

- Follow PSR-12 coding standards
- Use meaningful variable names
- Add comments for complex logic
- Keep methods focused and single-purpose

#### Commit Messages

Use clear, descriptive commit messages:

```bash
# Good
git commit -m "Add: WebP conversion method with quality control"
git commit -m "Fix: Aspect ratio calculation for portrait images"
git commit -m "Docs: Add watermark positioning guide"

# Avoid
git commit -m "update"
git commit -m "fix bug"
```

#### Documentation

- Update README.md for new features
- Add detailed guides in `docs/src/content/docs/guides/`
- Include code examples
- Update API reference

### What to Contribute

We're looking for:

#### Features
- New image processing methods
- Format support (AVIF, HEIC, etc.)
- Batch processing utilities
- Performance optimizations

#### Documentation
- More examples and use cases
- Tutorials for specific scenarios
- Translations
- Video guides

#### Tests
- Additional test cases
- Performance benchmarks
- Browser compatibility tests

#### Bug Fixes
- Check [Issues](https://github.com/Kenura-R-Gunarathna/imagick-image-processor/issues)
- Fix reported bugs
- Improve error handling

### Getting Help

Need help contributing?

- 💬 [Open a Discussion](https://github.com/Kenura-R-Gunarathna/imagick-image-processor/discussions)
- 🐛 [Report a Bug](https://github.com/Kenura-R-Gunarathna/imagick-image-processor/issues/new)
- 💡 [Request a Feature](https://github.com/Kenura-R-Gunarathna/imagick-image-processor/issues/new)
- 📧 Email: kenuragunarathna@gmail.com

## Code of Conduct

Be respectful and constructive. We're all here to learn and improve the library together.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Recognition

Contributors will be acknowledged in:
- README.md
- Release notes
- Documentation

Thank you for helping make Imagick Image Processor better! 🎉
