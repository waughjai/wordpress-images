<?php
	/*
	Plugin Name:  WAJ Image
	Plugin URI:   https://github.com/waughjai/waj-image-loaders
	Description:  Classes & shortcodes for making image HTML generation simpler for WordPress.
	Version:      1.0.0
	Author:       Jaimeson Waugh
	Author URI:   https://www.jaimeson-waugh.com
	License:      GPL2
	License URI:  https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain:  waj-image-loaders
	*/

declare( strict_types = 1 );
namespace WAJ\WAJImage
{
	require_once( 'vendor/autoload.php' );

	use WaughJ\WPImage\WPThemeImage;
	use WaughJ\WPImage\WPUploadImage;
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\WPImage\WPThemePicture;
	use WaughJ\WPImage\WPUploadPicture;
	use WaughJ\HTMLPicture\HTMLPicture;
	use function WaughJ\TestHashItem\TestHashItemString;

	add_shortcode
	(
		'theme-image',
		image_function_generator( WPThemeImage::class )
	);

	add_shortcode
	(
		'upload-image',
		image_function_generator( WPUploadImage::class )
	);

	add_shortcode
	(
		'image',
		function ( $atts )
		{
			$src = TestHashItemString( $atts, 'src' );
			if ( $src )
			{
				unset( $atts[ 'src' ] );
				return ( string )( new HTMLImage( $src, null, $atts ) );
			}
			return '';
		}
	);

	add_shortcode
	(
		'theme-picture',
		picture_function_generator( WPThemePicture::class )
	);

	add_shortcode
	(
		'upload-picture',
		picture_function_generator( WPUploadPicture::class )
	);

	add_shortcode
	(
		'picture',
		picture_function_generator( HTMLPicture::class )
	);

	function image_function_generator( string $class )
	{
		return function ( $atts ) use ( $class )
		{
			$src = TestHashItemString( $atts, 'src' );
			if ( $src )
			{
				unset( $atts[ 'src' ] );
				return ( string )( new $class( $src, $atts ) );
			}
			return '';
		};
	}

	function picture_function_generator( string $class )
	{
		return function ( $atts ) use ( $class )
		{
			$src = TestHashItemString( $atts, 'src' );
			$ext = TestHashItemString( $atts, 'ext' );
			$sizes = TestHashItemString( $atts, 'sizes' );
			if ( $src && $ext )
			{
				unset( $atts[ 'src' ], $atts[ 'ext' ] );
				return ( string )( new $class( $src, $ext, $sizes, $atts ) );
			}
			return '';
		};
	}
}
