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
	}

	public function testThemeImage()
	{
		$shortcode = do_shortcode( '[theme-image src="hello.png"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/themes/example/hello.png"', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
	}
}
