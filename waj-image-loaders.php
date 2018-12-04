<?php
	/*
	Plugin Name:  WAJ Image
	Plugin URI:   https://github.com/waughjai/waj-image-loaders
	Description:  Classes & shortcodes for making image HTML generation simpler for WordPress.
	Version:      1.1.0
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
		ImageShortcodeFunctionGenerator( WPThemeImage::class )
	);

	add_shortcode
	(
		'upload-image',
		function ( $atts )
		{
			$id = TestHashItemString( $atts, 'id' );
			$size = TestHashItemString( $atts, 'size' );
			if ( $id )
			{
				// Make sure we don't propagate this to the HTML Attributes list.
				unset( $atts[ 'id' ] );
				return ( string )( new WPUploadImage( intval( $id ), $size, $atts ) );
			}
			return '';
		}
	);

	add_shortcode
	(
		'image',
		function ( $atts )
		{
			$src = TestHashItemString( $atts, 'src' );
			if ( $src )
			{
				// Make sure we don't propagate this to the HTML Attributes list.
				unset( $atts[ 'src' ] );
				return ( string )( new HTMLImage( $src, null, $atts ) );
			}
			return '';
		}
	);

	add_shortcode
	(
		'theme-picture',
		PictureShortcodeFunctionGenerator( WPThemePicture::class )
	);

	add_shortcode
	(
		'upload-picture',
		function ( $atts )
		{
			$id = TestHashItemString( $atts, 'id' );
			if ( $id )
			{
				$atts = TransformShortcodeAttributesToElementAttributes( $atts );
				// Make sure we don't propagate these to the HTML Attributes list.
				unset( $atts[ 'id' ] );
				return ( string )( new WPUploadPicture( intval( $id ), $atts ) );
			}
			return '';
		}
	);

	add_shortcode
	(
		'picture',
		PictureShortcodeFunctionGenerator( HTMLPicture::class )
	);

	function ImageShortcodeFunctionGenerator( string $class )
	{
		return function ( $atts ) use ( $class )
		{
			$src = TestHashItemString( $atts, 'src' );
			if ( $src )
			{
				// Make sure we don't propagate this to the HTML Attributes list.
				unset( $atts[ 'src' ] );
				return ( string )( new $class( $src, $atts ) );
			}
			return '';
		};
	}

	function PictureShortcodeFunctionGenerator( string $class )
	{
		return function ( $atts ) use ( $class )
		{
			$src = TestHashItemString( $atts, 'src' );
			$ext = TestHashItemString( $atts, 'ext' );
			$sizes = TestHashItemString( $atts, 'sizes' );
			if ( $src && $ext && $sizes )
			{
				$atts = TransformShortcodeAttributesToElementAttributes( $atts );
				// Make sure we don't propagate these to the HTML Attributes list.
				unset( $atts[ 'src' ], $atts[ 'ext' ], $atts[ 'sizes' ] );
				return ( string )( new $class( $src, $ext, $sizes, $atts ) );
			}
			return '';
		};
	}

	function TransformShortcodeAttributesToElementAttributes( array $atts ) : array
	{
		// Initialize
		$element_atts = [];
		$prefixes = [];
		$prefix_lengths = [];

		foreach ( PICTURE_ELEMENTS as $element )
		{
			// Where we will put the new versions o' the attributes for each element.
			$element_atts[ $element ] = [];
			// We need these for the lengths, so we might as well save these.
			$prefixes[ $element ] = "{$element}-";
			// Optimization: save string lengths so we don't recalculate these for every attribute x element, but can just reference them directly.
			$prefix_lengths[ $element ] = strlen( $prefixes[ $element ] );
		}

		// Convert attributes
		foreach ( $atts as $attribute_key => $attribute_value )
		{
			foreach ( PICTURE_ELEMENTS as $element )
			{
				$prefix = $prefixes[ $element ];
				$prefix_length = $prefix_lengths[ $element ];
				$starts_with_prefix = ( strpos( $attribute_key, $prefix ) === 0 );
				if ( $starts_with_prefix )
				{
					$attribute_key_without_prefix = substr( $attribute_key, $prefix_length );
					$element_atts[ $element ][ $attribute_key_without_prefix ] = $attribute_value; // Set new version o' attribute.
					unset( $atts[ $attribute_key ] ); // Get rid of ol' version o' attribute.
				}
			}
		}

		// Finally, add all new versions o' attributes to original attributes.
		foreach ( PICTURE_ELEMENTS as $element )
		{
			$atts[ "{$element}-attributes" ] = $element_atts[ $element ];
		}

		return $atts;
	}

	const PICTURE_ELEMENTS = [ 'img', 'picture', 'source' ];
}
