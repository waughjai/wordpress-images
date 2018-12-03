<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\HTMLPicture\HTMLPicture;
	use function WaughJ\WPGetImageSizes\WPGetImageSizes;

	class WPUploadPicture extends HTMLPicture
	{
		public function __construct( string $src, string $extension, $sizes = null, array $attributes = [] )
		{
			$loader = WPUploadImage::getFileLoader( $attributes );
			unset( $attributes[ 'directory' ] ); // Make sure we don't keep this is an attribute that gets passed into the HTML itself.
			$attributes[ 'loader' ] = $loader;

			if ( $sizes === null || $sizes === 'auto' )
			{
				$sizes = self::getDefaultSizes();
			}

			parent::__construct( $src, $extension, $sizes, $attributes );
		}

		private function getDefaultSizes() : array
		{
			$image_sizes = WPGetImageSizes();
			$new_sizes = [];
			foreach ( $image_sizes as $size )
			{
				$new_sizes[] = [ 'w' => $size->getWidth(), 'h' => $size->getHeight() ];
			}
			return $new_sizes;
		}
	}
}
