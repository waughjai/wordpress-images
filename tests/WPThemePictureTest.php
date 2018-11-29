<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPImage\WPThemePicture;

require_once( 'MockWordPress.php' );

class WPThemePictureTest extends TestCase
{
	public function testBasicPicture()
	{
		$picture = new WPThemePicture( 'photo', 'jpg', '320w 240h, 800w 400h, 1200w 800h' );
		$this->assertContains( ' src="https://www.example.com/wp-content/themes/example/img/photo-320x240.jpg?m=', $picture->getHTML() );
	}
}
