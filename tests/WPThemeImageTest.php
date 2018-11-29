<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPImage\WPThemeImage;

require_once( 'MockWordPress.php' );

class WPThemeImageTest extends TestCase
{
	public function testBasicImage()
	{
		$image = new WPThemeImage( 'favicon.png' );
		$this->assertEquals( $image->getHTML(), '<img src="https://www.example.com/wp-content/themes/example/favicon.png?m=' . filemtime( getcwd() . '/favicon.png'  ) . '" alt="" />' );
	}

	public function testWithShareDirectory()
	{
		$image = new WPThemeImage( 'photo.jpg', [ 'directory' => 'img' ] );
		$this->assertEquals( $image->getHTML(), '<img src="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=' . filemtime( getcwd() . '/img/photo.jpg'  ) . '" alt="" />' );
	}

	public function testNoCache()
	{
		$image = new WPThemeImage( 'photo.jpg', [ 'directory' => 'img', 'show-version' => false ] );
		$this->assertEquals( $image->getHTML(), '<img src="https://www.example.com/wp-content/themes/example/img/photo.jpg" alt="" />' );
	}

	public function testWithExtraAttributes()
	{
		$image = new WPThemeImage( 'photo.jpg', [ 'directory' => 'img', 'class' => 'center-img portrait', 'width' => '320', 'height' => 320, 'alt' => 'Windmill Trails', 'srcset' => 'photo.jpg 320w, photo-2x.jpg 640w' ] );
		$this->assertContains( ' src="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=' . filemtime( getcwd() . '/img/photo.jpg'  ) . '"', $image->getHTML() );
		$this->assertContains( ' width="320"', $image->getHTML() );
		$this->assertContains( ' height="320"', $image->getHTML() );
		$this->assertContains( ' class="center-img portrait"', $image->getHTML() );
		$this->assertContains( ' alt="Windmill Trails"', $image->getHTML() );
		$this->assertContains( ' srcset="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=' . filemtime( getcwd() . '/img/photo.jpg'  ) . ' 320w, https://www.example.com/wp-content/themes/example/img/photo-2x.jpg?m=' . filemtime( getcwd() . '/img/photo-2x.jpg'  ) . ' 640w"', $image->getHTML() );
	}

	public function testSetDefault()
	{
		WPThemeImage::setDefaultSharedDirectory( 'img' );
		$image = new WPThemeImage( 'photo.jpg' );
		$this->assertEquals( '<img src="https://www.example.com/wp-content/themes/example/img/photo.jpg?m=' . filemtime( getcwd() . '/img/photo.jpg' ) . '" alt="" />', $image->getHTML() );
	}
}
