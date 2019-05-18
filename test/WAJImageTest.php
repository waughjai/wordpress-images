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

	public function testBasicPicture()
	{
		$shortcode = do_shortcode( '[picture src="demo.png" sizes="150w 150h, 300w 300h"]' );
		$this->assertEquals( '<picture><source srcset="demo-150x150.png" media="(max-width:150px)"><source srcset="demo-300x300.png" media="(min-width:151px)"><img src="demo-150x150.png" alt="" /></picture>', $shortcode );
	}

	public function testBasicPictureAttributes()
	{
		$shortcode = do_shortcode( '[picture src="demo.png" sizes="150w 150h, 300w 300h" picture-id="viewer" source-class="image-source" img-width="800"]' );
		$this->assertEquals( '<picture id="viewer"><source class="image-source" srcset="demo-150x150.png" media="(max-width:150px)"><source class="image-source" srcset="demo-300x300.png" media="(min-width:151px)"><img src="demo-150x150.png" width="800" alt="" /></picture>', $shortcode );
	}

	public function testNilThemeImage()
	{
		$shortcode = do_shortcode( '[theme-image]' );
		$this->assertEquals( '', $shortcode );
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

	public function testThemeImagePartiallyMissingImage()
	{
		$shortcode = do_shortcode( '[theme-image src="missing.png" alt="An Image" id="special" class="image" srcset="demo-300x300.png 300w"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/themes/example/missing.png"', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/themes/example/demo-300x300.png?m=', $shortcode );
	}

	public function testNilUploadImage()
	{
		$shortcode = do_shortcode( '[upload-image]' );
		$this->assertEquals( '', $shortcode );
	}

	public function testUploadImage()
	{
		$shortcode = do_shortcode( '[upload-image media-id="1" id="first-image"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' id="first-image"', $shortcode );
	}

	public function testUploadImageJustID()
	{
		$shortcode = do_shortcode( '[upload-image id="1"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringNotContainsString( ' id="', $shortcode );
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

	public function testUploadImageNonexistentImage()
	{
		$shortcode = do_shortcode( '[upload-image media-id="512"]' );
		$this->assertEquals( '', $shortcode );
	}

	public function testUploadImageSizes()
	{
		$shortcode = do_shortcode( '[upload-image media-id="1" size="responsive"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( ' sizes="(max-width: 150px) 150px, (max-width: 300px) 300px, (max-width: 768px) 768px, 1024px"', $shortcode );
		$this->assertStringNotContainsString( ' size="', $shortcode );

		$shortcode = do_shortcode( '[upload-image media-id="1" size="thumbnail"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );
		$this->assertStringNotContainsString( ' size="', $shortcode );

		$shortcode = do_shortcode( '[upload-image media-id="1" size="medium"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo-300x300.png"', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );
		$this->assertStringNotContainsString( ' size="', $shortcode );

		$shortcode = do_shortcode( '[upload-image media-id="1" size="medium_large"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo-768x768.png"', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );
		$this->assertStringNotContainsString( ' size="', $shortcode );

		$shortcode = do_shortcode( '[upload-image media-id="1" size="large"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/demo-1024x1024.png"', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );
		$this->assertStringNotContainsString( ' size="', $shortcode );
	}

	public function testUploadImageSizesSomeMissing()
	{
		$shortcode = do_shortcode( '[upload-image media-id="3" size="responsive"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/mountain-150x150.jpg?m=', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/mountain-150x150.jpg?m=', $shortcode );
		$this->assertStringContainsString( 'https://www.example.com/wp-content/uploads/2018/12/mountain-300x300.jpg 300w, ', $shortcode );
		$this->assertStringContainsString( ' sizes="(max-width: 150px) 150px, (max-width: 300px) 300px, (max-width: 768px) 768px, 1024px"', $shortcode );
		$this->assertStringNotContainsString( ' size="', $shortcode );

		$shortcode = do_shortcode( '[upload-image media-id="4" size="responsive"]' );
		$this->assertStringContainsString( ' src="https://www.example.com/wp-content/uploads/2018/12/forest-150x150.jpg"', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/forest-150x150.jpg 150w, ', $shortcode );
		$this->assertStringContainsString( 'https://www.example.com/wp-content/uploads/2018/12/forest-300x300.jpg?m=', $shortcode );
		$this->assertStringContainsString( ' sizes="(max-width: 150px) 150px, (max-width: 300px) 300px, (max-width: 768px) 768px, 1024px"', $shortcode );
		$this->assertStringNotContainsString( ' size="', $shortcode );
	}

	public function testThemePicture()
	{
		$shortcode = do_shortcode( '[theme-picture src="demo" ext="png" sizes="150w 150h, 300w 300h"]' );
		$this->assertStringContainsString( '<picture>', $shortcode );
		$this->assertStringContainsString( '<source', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/themes/example/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/themes/example/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' media="(min-width:151px)"', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );

		$shortcode = do_shortcode( '[theme-picture src="demo.png" sizes="150w 150h, 300w 300h"]' );
		$this->assertStringContainsString( '<picture>', $shortcode );
		$this->assertStringContainsString( '<source', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/themes/example/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/themes/example/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' media="(min-width:151px)"', $shortcode );
	}

	public function testThemePictureNil()
	{
		$shortcode = do_shortcode( '[theme-picture]' );
		$this->assertEquals( '', $shortcode );
	}

	public function testThemePictureVersionless()
	{
		$shortcode = do_shortcode( '[theme-picture src="demo.png" sizes="150w 150h, 300w 300h" show-version="false"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/themes/example/demo-150x150.png', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/themes/example/demo-150x150.png"', $shortcode );
		$this->assertStringContainsString( ' media="(min-width:151px)"', $shortcode );
		$this->assertStringNotContainsString( '?m=', $shortcode );
		$this->assertStringNotContainsString( ' show-version="', $shortcode );
	}

	public function testThemePictureAttributes()
	{
		$shortcode = do_shortcode( '[theme-picture src="demo.png" sizes="150w 150h, 300w 300h" img-alt="This is a Demo" img-class="imagine" show-version="false" picture-id="brumble" source-class="image-src"]' );
		$image = $this->getImageTag( $shortcode );
		$sources = $this->getSourcesTags( $shortcode );
		$picture_head = $this->getPictureHead( $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/themes/example/demo-150x150.png', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/themes/example/demo-150x150.png', $shortcode );
		$this->assertStringContainsString( ' media="(min-width:151px)"', $shortcode );
		$this->assertStringNotContainsString( ' show-version="', $shortcode );

		// Individual tags.
		$this->assertStringContainsString( ' id="brumble"', $picture_head );
		$this->assertStringContainsString( ' alt="This is a Demo"', $image );
		$this->assertStringContainsString( ' class="imagine"', $image );

		foreach ( $sources as $source )
		{
			$this->assertStringContainsString( ' class="image-src"', $source );
		}
	}

	public function testThemePictureMissingFiles()
	{
		$shortcode = do_shortcode( '[theme-picture src="demo.png" sizes="150w 150h, 300w 300h, 600w 600h"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/themes/example/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/themes/example/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( 'https://www.example.com/wp-content/themes/example/demo-600x600.png"', $shortcode );
	}

	public function testUploadPicture()
	{
		$shortcode = do_shortcode( '[upload-picture media-id="1"]' );
		$this->assertStringContainsString( '<picture>', $shortcode );
		$this->assertStringContainsString( '<source', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' media="(max-width:150px)"', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );
	}

	public function testUploadPictureNil()
	{
		$shortcode = do_shortcode( '[upload-picture]' );
		$this->assertEquals( '', $shortcode );
	}

	public function testUploadPictureFallback()
	{
		$shortcode = do_shortcode( '[upload-picture id="1"]' );
		$this->assertStringContainsString( '<picture>', $shortcode );
		$this->assertStringContainsString( '<source', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' media="(max-width:150px)"', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );
		$this->assertStringNotContainsString( ' id="', $shortcode );
	}

	public function testUploadPictureVersionless()
	{
		$shortcode = do_shortcode( '[upload-picture media-id="1" show-version="false"]' );
		$this->assertStringContainsString( '<picture>', $shortcode );
		$this->assertStringContainsString( '<source', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png', $shortcode );
		$this->assertStringContainsString( '<img src="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png"', $shortcode );
		$this->assertStringContainsString( ' alt=""', $shortcode );
		$this->assertStringContainsString( ' media="(max-width:150px)"', $shortcode );
		$this->assertStringNotContainsString( ' sizes="', $shortcode );
		$this->assertStringNotContainsString( '?m=', $shortcode );
		$this->assertStringNotContainsString( ' show-version="', $shortcode );
	}

	public function testUploadPictureAttributes()
	{
		$shortcode = do_shortcode( '[upload-picture media-id="1" picture-width="600" img-alt="Blabba" img-class="babbage" source-height="255"]' );
		$image = $this->getImageTag( $shortcode );
		$sources = $this->getSourcesTags( $shortcode );
		$picture_head = $this->getPictureHead( $shortcode );

		// Individual tags.
		$this->assertStringContainsString( ' width="600"', $picture_head );
		$this->assertStringContainsString( ' alt="Blabba"', $image );
		$this->assertStringContainsString( ' class="babbage"', $image );

		foreach ( $sources as $source )
		{
			$this->assertStringContainsString( ' height="255"', $source );
		}
	}

	public function testUploadPictureNonexistentFile()
	{
		$shortcode = do_shortcode( '[upload-picture media-id="423"]' );
		$this->assertEquals( '', $shortcode );
	}

	public function testUploadPictureMissingFile()
	{
		$shortcode = do_shortcode( '[upload-picture media-id="3"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/mountain-150x150.jpg?m=', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/mountain-300x300.jpg"', $shortcode );

		$shortcode = do_shortcode( '[upload-picture media-id="4"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/forest-150x150.jpg"', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/forest-300x300.jpg?m=', $shortcode );
	}

	public function testThumbnail()
	{
		$shortcode = do_shortcode( '[thumbnail]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/demo-150x150.png?m=', $shortcode );
	}

	public function testThumbnailID()
	{
		$shortcode = do_shortcode( '[thumbnail post-id="3"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/mountain-150x150.jpg?m=', $shortcode );
		$this->assertStringNotContainsString( ' post-id="', $shortcode );
	}

	public function testThumbnailAttributes()
	{
		$shortcode = do_shortcode( '[thumbnail post-id="3" picture-id="cypress" img-alt="Colorful" source-height="255"]' );
		$image = $this->getImageTag( $shortcode );
		$sources = $this->getSourcesTags( $shortcode );
		$picture_head = $this->getPictureHead( $shortcode );
		$this->assertStringContainsString( ' id="cypress"', $picture_head );
		$this->assertStringContainsString( ' alt="Colorful"', $image );
		foreach ( $sources as $source )
		{
			$this->assertStringContainsString( ' height="255"', $source );
		}
	}

	public function testThumbnailMissingFiles()
	{
		$shortcode = do_shortcode( '[thumbnail post-id="3"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/mountain-150x150.jpg?m=', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/mountain-300x300.jpg"', $shortcode );

		$shortcode = do_shortcode( '[thumbnail post-id="4"]' );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/forest-150x150.jpg"', $shortcode );
		$this->assertStringContainsString( ' srcset="https://www.example.com/wp-content/uploads/2018/12/forest-300x300.jpg?m=', $shortcode );
	}

	public function testThumbnailNonExistentFile()
	{
		$shortcode = do_shortcode( '[thumbnail post-id="243" picture-id="cypress" img-alt="Colorful" source-height="255"]' );
		$this->assertEquals( '', $shortcode );
	}

	private function getSourcesTags( string $text ) : array
	{
		return $this->getElementTags( $text, 'source', false );
	}

	private function getPictureHead( string $text ) : string
	{
		return $this->getElementTags( $text, 'picture', false )[ 0 ];
	}

	private function getImageTag( string $text ) : string
	{
		return $this->getElementTags( $text, 'img', false )[ 0 ];
	}

	private function getElementTags( string $text, string $element, bool $end_tag ) : array
	{
		$start_string = "<{$element}";
		$end_string = ( $end_tag ) ? "</{$element}>" : ">";

		$items = [];
		while( true )
		{
			$start = strpos( $text, $start_string );
			if ( $start === false ) { break; }
			$text_from_start = substr( $text, $start );
			$end = strpos( $text_from_start, $end_string );
			$item = substr( $text_from_start, 0, $end + 1 );
			$items[] = $item;
			$text = str_replace( $item, '', $text );
		}
		return $items;
	}
}
