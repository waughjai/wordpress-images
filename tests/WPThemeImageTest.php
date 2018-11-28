<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPImage\WPThemeImage;

require_once( 'MockWordPress.php' );

class WPThemeImageTest extends TestCase
{
	public function testBasicImage()
	{
		$image = new WPThemeImage( 'favicon.png' );
		$this->assertEquals( $image->getHTMLString(), '<img src="https://www.example.com/wp-content/themes/example/favicon.png?m=' . filemtime( getcwd() . '/favicon.png'  ) . '" alt="" />' );
	}

	public function testWithShareDirectory()
	{
		$image = new WPThemeImage( 'photo.jpg', [ 'directory' => 'img' ] );
		$this->assertEquals( $image->getHTMLString(), '<img src="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=' . filemtime( getcwd() . '/img/photo.jpg'  ) . '" alt="" />' );
	}

	public function testNoCache()
	{
		$image = new WPThemeImage( 'photo.jpg', [ 'directory' => 'img', 'show-version' => false ] );
		$this->assertEquals( $image->getHTMLString(), '<img src="https://www.example.com/wp-content/themes/example/img/photo.jpg" alt="" />' );
	}

	public function testWithExtraAttributes()
	{
		$image = new WPThemeImage( 'photo.jpg', [ 'directory' => 'img', 'class' => 'center-img portrait', 'width' => '1200', 'height' => 320, 'alt' => 'Windmill Trails' ] );
		$this->assertContains( ' src="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=' . filemtime( getcwd() . '/img/photo.jpg'  ) . '"', $image->getHTMLString() );
		$this->assertContains( ' width="1200"', $image->getHTMLString() );
		$this->assertContains( ' height="320"', $image->getHTMLString() );
		$this->assertContains( ' class="center-img portrait"', $image->getHTMLString() );
		$this->assertContains( ' alt="Windmill Trails"', $image->getHTMLString() );
	}

	public function testSetDefault()
	{
		WPThemeImage::setDefaultSharedDirectory( 'img' );
		$image = new WPThemeImage( 'photo.jpg' );
		$this->assertEquals( '<img src="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=' . filemtime( getcwd() . '/img/photo.jpg' ) . '" alt="" />', $image->getHTMLString() );
	}
}
