// @ts-check
import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';

// https://astro.build/config
export default defineConfig({
	integrations: [
		starlight({
			title: 'Imagick Image Processor',
			description: 'A powerful PHP library for image processing using the Imagick extension',
			social: [
				{
					icon: 'github',
					label: 'GitHub',
					href: 'https://github.com/Kenura-R-Gunarathna/imagick-image-processor'
				}
			],
			sidebar: [
				{
					label: 'Getting Started',
					items: [
						{ label: 'Introduction', slug: 'index' },
						{ label: 'Installation', slug: 'getting-started/installation' },
						{ label: 'Quick Start', slug: 'getting-started/quick-start' },
						{ label: 'Testing & Contributing', slug: 'getting-started/testing' },
					],
				},
				{
					label: 'Guides',
					items: [
						{ label: 'Resizing Images', slug: 'guides/resizing' },
						{ label: 'Compressing Images', slug: 'guides/compressing' },
						{ label: 'Adding Watermarks', slug: 'guides/watermarks' },
						{ label: 'Adjusting Opacity', slug: 'guides/opacity' },
						{ label: 'WebP Conversion', slug: 'guides/webp-conversion' },
						{ label: 'Combined Operations', slug: 'guides/combined-operations' },
					],
				},
				{
					label: 'API Reference',
					items: [
						{ label: 'ImageProcessor', slug: 'reference/imageprocessor' },
						{ label: 'resizeImage', slug: 'reference/resize-image' },
						{ label: 'compressToJpg', slug: 'reference/compress-to-jpg' },
						{ label: 'addWatermark', slug: 'reference/add-watermark' },
						{ label: 'addOpacity', slug: 'reference/add-opacity' },
						{ label: 'convertToWebP', slug: 'reference/convert-to-webp' },
					],
				},
			],
		}),
	],
});
