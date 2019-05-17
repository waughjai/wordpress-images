<?php
	/*
	Plugin Name:  WAJ Image
	Plugin URI:   https://github.com/waughjai/waj-image-loaders
	Description:  Classes & shortcodes for making image HTML generation simpler for WordPress.
	Version:      2.2.2
	Author:       Jaimeson Waugh
	Author URI:   https://www.jaimeson-waugh.com
	License:      GPL2
	License URI:  https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain:  waj-image-loaders
	*/

declare( strict_types = 1 );
namespace WAJ\WAJImage;

require_once( 'vendor/autoload.php' );

use WaughJ\FileLoader\MissingFileException;
use WaughJ\HTMLImage\HTMLImage;
use WaughJ\HTMLPicture\HTMLPicture;
use function WaughJ\TestHashItem\TestHashItemExists;
use function WaughJ\TestHashItem\TestHashItemString;
use WaughJ\WPThemeImage\WPThemeImage;
use WaughJ\WPThemeOption\WPThemeOptionsPageManager;
use WaughJ\WPThemeOption\WPThemeOptionsSection;
use WaughJ\WPThemeOption\WPThemeOption;
use WaughJ\WPThemePicture\WPThemePicture;
use WaughJ\WPUploadImage\WPMissingMediaException;
use WaughJ\WPUploadImage\WPUploadImage;
use WaughJ\WPUploadPicture\WPUploadPicture;
use WaughJ\WPPostThumbnail\WPPostThumbnail;



//
//  ADMIN THEME IMAGE DIRECTORY
//
//////////////////////////////////////////////////////////

	$page = WPThemeOptionsPageManager::initializeIfNotAlreadyInitialized( 'directories', 'Directories' );
	$section = new WPThemeOptionsSection( $page, 'theme-image', 'Theme Image' );
	$option = new WPThemeOption( $section, 'theme-image-directory', 'Theme Image Directory' );
	WPThemeImage::setDefaultSharedDirectory( $option->getOptionValue() );



//
//  SHORTCODES
//
//////////////////////////////////////////////////////////

	add_shortcode
	(
		'thumbnail',
		function( $args )
		{
			$post_id = intval( $args[ 'post-id' ] ?? get_the_ID() );

			if ( is_array( $args ) )
			{
				unset( $args[ 'post-id' ] );
			}
			else
			{
				$args = [];
			}

			try
			{
				return ( string )( new WPPostThumbnail( $post_id, TransformShortcodeAttributesToElementAttributes( $args ) ) );
			}
			catch ( WPMissingMediaException $e )
			{
				return ''; // No fallback info to give, so just return nothing.
			}
			catch ( MissingFileException $e ) // Since shortcodes should be mo’ user-friendly, we don’t want any website-breaking exceptions getting through.
			{
				return ( string )( $e->getFallbackContent() );
			}
		}
	);

	add_shortcode
	(
		'theme-image',
		function ( $atts )
		{
			$src = ( string )( $atts[ 'src' ] ?? '' );
			if ( $src !== '' )
			{
				// Make sure we don't propagate this to the HTML Attributes list.
				unset( $atts[ 'src' ] );

				// Unfortunately, WordPress treats all atts as strings & PHP considers the string “false” to be truthy,
				// so we must convert it to a true false boolean.
				$atts = FixShowVersionAtt( $atts );

				try
				{
					return ( string )( new WPThemeImage( $src, $atts ) );
				}
				catch ( MissingFileException $e ) // Since shortcodes should be mo’ user-friendly, we don’t want any website-breaking exceptions getting through.
				{
					return ( string )( $e->getFallbackContent() );
				}
			}
			return '';
		}
	);

	add_shortcode
	(
		'upload-image',
		function ( $atts )
		{
			$id = TestHashItemString( $atts, 'media-id' );
			$size = TestHashItemString( $atts, 'size' );
			if ( $id )
			{
				// Make sure we don't propagate this to the HTML Attributes list.
				unset( $atts[ 'media-id' ], $atts[ 'size' ] );

				// Unfortunately, WordPress treats all atts as strings & PHP considers the string “false” to be truthy,
				// so we must convert it to a true false boolean.
				$atts = FixShowVersionAtt( $atts );

				try
				{
					return ( string )( new WPUploadImage( intval( $id ), $size, $atts ) );
				}
				catch ( WPMissingMediaException $e )
				{
					return ''; // No fallback info to give, so just return nothing.
				}
				catch ( MissingFileException $e ) // Since shortcodes should be mo’ user-friendly, we don’t want any website-breaking exceptions getting through.
				{
					return ( string )( $e->getFallbackContent() );
				}
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
			$id = TestHashItemString( $atts, 'media-id' );
			if ( $id )
			{
				$atts = TransformShortcodeAttributesToElementAttributes( $atts );
				// Make sure we don't propagate these to the HTML Attributes list.
				unset( $atts[ 'media-id' ] );

				// Unfortunately, WordPress treats all atts as strings & PHP considers the string “false” to be truthy,
				// so we must convert it to a true false boolean.
				$atts = FixShowVersionAtt( $atts );

				try
				{
					return ( string )( new WPUploadPicture( intval( $id ), $atts ) );
				}
				catch ( WPMissingMediaException $e )
				{
					return ''; // No fallback info to give, so just return nothing.
				}
				catch ( MissingFileException $e ) // Since shortcodes should be mo’ user-friendly, we don’t want any website-breaking exceptions getting through.
				{
					return ( string )( $e->getFallbackContent() );
				}
			}
			return '';
		}
	);

	add_shortcode
	(
		'picture',
		PictureShortcodeFunctionGenerator( HTMLPicture::class )
	);



//
//  HELPER FUNCTIONS
//
//////////////////////////////////////////////////////////

	function PictureShortcodeFunctionGenerator( string $class )
	{
		return function ( $atts ) use ( $class )
		{
			$src = TestHashItemString( $atts, 'src' );
			$ext = TestHashItemString( $atts, 'ext' );
			$sizes = TestHashItemString( $atts, 'sizes' );
			if ( $src && $sizes )
			{
				// If extension not specifically given, hint it through the src.
				if ( $ext === null )
				{
					$parts = explode( '.', $src );
					$ext = ( count( $parts ) > 1 ) ? array_pop( $parts ) : '';
					$src = implode( '.', $parts );
				}

				// Unfortunately, WordPress treats all atts as strings & PHP considers the string “false” to be truthy,
				// so we must convert it to a true false boolean.
				$atts = FixShowVersionAtt( $atts );

				$atts = TransformShortcodeAttributesToElementAttributes( $atts );
				// Make sure we don't propagate these to the HTML Attributes list.
				unset( $atts[ 'src' ], $atts[ 'ext' ], $atts[ 'sizes' ] );

				try
				{
					if ( $class === HTMLPicture::class )
					{
						return ( string )( HTMLPicture::generate( $src, $ext, $sizes, $atts ) );
					}
					return ( string )( new $class( $src, $ext, $sizes, $atts ) );
				}
				catch ( WPMissingMediaException $e )
				{
					return ''; // No fallback info to give, so just return nothing.
				}
				catch ( MissingFileException $e ) // Since shortcodes should be mo’ user-friendly, we don’t want any website-breaking exceptions getting through.
				{
					return ( string )( $e->getFallbackContent() );
				}
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

	function FixShowVersionAtt( array $atts ) : array
	{
		if ( ( $atts[ 'show-version' ] ?? null ) === 'false' )
		{
			$atts[ 'show-version' ] = false;
		}
		return $atts;
	}

	const PICTURE_ELEMENTS = [ 'img', 'picture', 'source' ];
