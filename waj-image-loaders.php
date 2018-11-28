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

	require_once( 'vendor/autoload.php' );

	use WaughJ\WPImage\WPThemeImage;
	use WaughJ\WPImage\WPUploadsImage;
	use WaughJ\HTMLImage\HTMLImage;
	use function WaughJ\TestHashItem\TestHashItemString;

	add_shortcode
	(
		'theme-image',
		function( $atts )
		{
			$src = TestHashItemString( $atts, 'src' );
			if ( $src )
			{
				unset( $atts[ 'src' ] );
				return new WPThemeImage( $src, $atts );
			}
			return '';
		}
	);

	add_shortcode
	(
		'upload-image',
		function( $atts )
		{
			$src = TestHashItemString( $atts, 'src' );
			if ( $src )
			{
				unset( $atts[ 'src' ] );
				return new WPUploadsImage( $src, $atts );
			}
			return '';
		}
	);

	add_shortcode
	(
		'image',
		function( $atts )
		{
			$src = TestHashItemString( $atts, 'src' );
			if ( $src )
			{
				unset( $atts[ 'src' ] );
				return new HTMLImage( $src, $atts );
			}
			return '';
		}
	);
