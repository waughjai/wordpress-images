<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\HTMLPicture\HTMLPicture;

	class WPUploadPicture extends HTMLPicture
	{
		public function __construct( string $src, string $extension, $sizes, array $attributes = [] )
		{
			$loader = WPUploadImage::getFileLoader( $attributes );
			unset( $attributes[ 'directory' ] ); // Make sure we don't keep this is an attribute that gets passed into the HTML itself.
			$attributes[ 'loader' ] = $loader;
			parent::__construct( $src, $extension, $sizes, $attributes );
		}
	}
}
