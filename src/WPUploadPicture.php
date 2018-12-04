<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\HTMLPicture\HTMLPicture;
	use function WaughJ\WPGetImageSizes\WPGetImageSizes;

	class WPUploadPicture extends HTMLPicture
	{
		public function __construct( int $id, array $attributes = [] )
		{
			$attributes[ 'loader' ] = WPUploadImage::getFileLoader();
			$full_url = wp_get_attachment_image_src( $id, 'full' )[ 0 ];
			$extension = $attributes[ 'loader' ]->getExtension( $full_url );
			$src = str_replace( '.' . $extension, '',  WPUploadImage::filterUploadDir( $full_url ) );
			parent::__construct( $src, $extension, self::getDefaultSizes(), $attributes );
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
