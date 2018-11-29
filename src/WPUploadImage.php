<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\FileLoader\FileLoader;

	class WPUploadImage extends HTMLImage
	{
		public function __construct( string $src, array $attributes = [] )
		{
			$loader = self::getFileLoader( $attributes );
			unset( $attributes[ 'directory' ] ); // Make sure we don't keep this is an attribute that gets passed into the HTML itself.
			parent::__construct( $src, $loader, $attributes );
		}

		public static function getFileLoader( array $attributes ) : FileLoader
		{
			$uploads = wp_upload_dir();
			$loader = new FileLoader([ 'directory-url' => $uploads[ 'url' ], 'directory-server' => $uploads[ 'path' ] ]);
			if ( isset( $attributes[ 'directory' ] ) && $attributes[ 'directory' ] )
			{
				$loader = $loader->changeSharedDirectory( $attributes[ 'directory' ] );
			}
			return $loader;
		}
	}
}
