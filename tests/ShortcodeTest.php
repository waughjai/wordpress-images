<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPImage\WPThemeImage;

require_once( 'MockWordPress.php' );
require_once( 'waj-image-loaders.php' );

class ShortcodeTest extends TestCase
{
	public function testImageShortcode()
	{
		$content = do_shortcode
		(
			'image',
			[
				'src' => 'img/photo.jpg'
			]
		);
		$this->assertContains( ' src="img/photo.jpg', $content );
	}

	public function testPictureShortcode()
	{
		$content = do_shortcode
		(
			'picture',
			[
				'src' => 'img/photo',
				'ext' => 'jpg',
				'sizes' => '320w 240h, 800w 400h, 1200w 800h'
			]
		);
		$this->assertContains( 'img/photo-320x240.jpg', $content );
	}

	public function testThemeImageShortcode()
	{
		$content = do_shortcode
		(
			'theme-image',
			[
				'src' => 'img/photo.jpg'
			]
		);
		$this->assertContains( ' src="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=', $content );
	}

	public function testThemePictureShortcode()
	{
		$content = do_shortcode
		(
			'theme-picture',
			[
				'src' => 'img/photo',
				'ext' => 'jpg',
				'sizes' => '320w 240h, 800w 400h, 1200w 800h'
			]
		);
		$this->assertContains( 'https://www.example.com/wp-content/themes/example/img/photo-320x240.jpg?m=', $content );
	}

	public function testUploadImageShortcode()
	{
		$content = do_shortcode
		(
			'upload-image',
			[
				'id' => '2'
			]
		);
		$this->assertContains( ' src="https://www.example.com/wp-content/uploads/2018/12/photo.jpg?m=', $content );
	}

	public function testUploadPictureShortcode()
	{
		$content = do_shortcode
		(
			'upload-picture',
			[
				'id' => '2',
				'img-class' => 'thumbnail'
			]
		);
		$this->assertContains( 'https://www.example.com/wp-content/uploads/2018/12/photo-300x300.jpg?m=', $content );
		$this->assertContains( ' class="thumbnail', $content );
	}
}
