<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPImage\WPUploadPicture;

require_once( 'MockWordPress.php' );

class WPUploadPictureTest extends TestCase
{
	public function testBasicPicture()
	{
		$picture = new WPUploadPicture( 'photo', 'jpg', '320w 240h, 800w 400h, 1200w 800h', [ 'directory' => [ '2018', '12' ] ] );
		$this->assertContains( ' src="https://www.example.com/wp-content/uploads/2018/12/photo-320x240.jpg?m=', $picture->getHTML() );
	}
}
