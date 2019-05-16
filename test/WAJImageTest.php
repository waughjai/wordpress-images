<?php

require_once( 'MockWordPress.php' );
require_once( 'waj-image.php' );
use PHPUnit\Framework\TestCase;

class WAJImageTest extends TestCase
{
	public function testBasicImage()
	{
		$shortcode = do_shortcode( '[image src="hello.png"]' );
		$this->assertStringContainsString( ' src="hello.png"', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertEquals( '<img src="hello.png" alt="" />', $shortcode );
	}

	public function testThemeImage()
	{
		$shortcode = do_shortcode( '[theme-image src="hello.png" id="special" class="image"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/themes/example/hello.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' id="special"', $shortcode );
		$this->assertStringContainsString( ' class="image"', $shortcode );
	}

	public function testThemeImageAltOverride()
	{
		$shortcode = do_shortcode( '[theme-image src="hello.png" alt="An Image" id="special" class="image"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/themes/example/hello.png?m=', $shortcode );
		$this->assertStringNotContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' alt="An Image"', $shortcode );
	}

	public function testThemeImageVersionless()
	{
		$shortcode = do_shortcode( '[theme-image src="hello.png" id="special" class="image" show-version="false"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/themes/example/hello.png"', $shortcode );
		$this->assertStringNotContainsString( '?m=', $shortcode );
		$this->assertStringNotContainsString( ' show-version="', $shortcode );
	}

	public function testThemeImageMissingImage()
	{
		$shortcode = do_shortcode( '[theme-image src="missing.png" alt="An Image" id="special" class="image"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/themes/example/missing.png"', $shortcode );
		$this->assertStringNotContainsString( '?m=', $shortcode );
	}

	public function testUploadImageSizes()
	{
		$shortcode = do_shortcode( '[upload-image media-id="1" size="responsive"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
	}

	public function testUploadImage()
	{
		$shortcode = do_shortcode( '[upload-image media-id="1" id="first-image"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' id="first-image"', $shortcode );
	}

	public function testUploadImageAltOverride()
	{
		$shortcode = do_shortcode( '[upload-image media-id="1" alt="Heyo Go"]' );
		$this->assertStringNotContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' alt="Heyo Go"', $shortcode );
	}

	public function testUploadImageVersionless()
	{
		$shortcode = do_shortcode( '[upload-image media-id="1" id="first-image" show-version="false"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo.png', $shortcode );
		$this->assertStringNotContainsString( '?m=', $shortcode );
	}

	public function testUploadImageMissingImage()
	{
		$shortcode = do_shortcode( '[upload-image media-id="2"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/photo.jpg', $shortcode );
		$this->assertStringNotContainsString( '?m=', $shortcode );
	}
}
