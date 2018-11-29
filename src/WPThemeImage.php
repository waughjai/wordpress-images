<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\Directory\Directory;
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\FileLoader\FileLoader;

	class WPThemeImage extends HTMLImage
	{
		public function __construct( string $src, array $attributes = [] )
		{
			$loader = self::getFileLoader( $attributes );
			unset( $attributes[ 'directory' ] ); // Make sure we don't keep this is an attribute that gets passed into the HTML itself.
			parent::__construct( $src, $loader, $attributes );
		}

		public static function setDefaultSharedDirectory( $directory )
		{
			self::$default_shared_directory = new Directory( $directory );
		}

		public static function getFileLoader( array $attributes ) : FileLoader
		{
			if ( !isset( $attributes[ 'directory' ] ) || !$attributes[ 'directory' ] )
			{
				$attributes[ 'directory' ] = self::$default_shared_directory;
			}
			return new FileLoader([ 'directory-url' => get_stylesheet_directory_uri(), 'directory-server' => get_stylesheet_directory(), 'shared-directory' => $attributes[ 'directory' ] ]);
		}

		private static $default_shared_directory = null;
	}
}
