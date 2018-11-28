<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPImage\WPUploadImage;

require_once( 'MockWordPress.php' );

class WPUploadImageTest extends TestCase
{
	public function testBasic()
	{
		$image = new WPUploadImage( '2018/12/demo.png' );
		$this->assertEquals( $image->getHTMLString(), '<img src="https://www.example.com/wp-content/uploads/2018/12/demo.png?m=' . filemtime( getcwd() . '/2018/12/demo.png'  ) . '" alt="" />' );
	}

	public function testSharedDirectories()
	{
		$image = new WPUploadImage( 'demo.png', [ 'directory' => '2018/12' ] );
		$this->assertEquals( $image->getHTMLString(), '<img src="https://www.example.com/wp-content/uploads/2018/12/demo.png?m=' . filemtime( getcwd() . '/2018/12/demo.png'  ) . '" alt="" />' );
	}

	public function testNoCache()
	{
		$image = new WPUploadImage( 'demo.png', [ 'directory' => '2018/12', 'show-version' => false ] );
		$this->assertEquals( $image->getHTMLString(), '<img src="https://www.example.com/wp-content/uploads/2018/12/demo.png" alt="" />' );
	}

	public function testWithExtraAttributes()
	{
		$image = new WPUploadImage( 'demo.png', [ 'directory' => '2018/12', 'class' => 'center-img portrait', 'width' => 800, 'height' => 600, 'alt' => 'King' ] );
		$this->assertContains( ' src="https://www.example.com/wp-content/uploads/2018/12/demo.png?m=' . filemtime( getcwd() . '/2018/12/demo.png'  ) . '"', $image->getHTMLString() );
		$this->assertContains( ' width="800"', $image->getHTMLString() );
		$this->assertContains( ' height="600"', $image->getHTMLString() );
		$this->assertContains( ' class="center-img portrait"', $image->getHTMLString() );
		$this->assertContains( ' alt="King"', $image->getHTMLString() );
	}
}
