<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\FileLoader\FileLoader;

	class WPUploadsImage extends WPImage
	{
		public function __construct( string $src, array $attributes = [] )
		{
			$uploads = wp_upload_dir();
			parent::__construct( $src, $attributes, new FileLoader([ 'directory-url' => $uploads[ 'url' ], 'directory-server' => $uploads[ 'path' ] ]) );
		}
	}
}
