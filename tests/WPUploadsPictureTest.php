<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPImage\WPUploadPicture;

require_once( 'MockWordPress.php' );

class WPUploadPictureTest extends TestCase
{
	public function testBasicPicture()
	{
		$picture = new WPUploadPicture( 2 );
		$this->assertContains( ' srcset="https://www.example.com/wp-content/uploads/2018/12/photo-300x300.jpg?m=', $picture->getHTML() );
	}
}
