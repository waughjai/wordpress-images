<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\Directory\Directory;
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\FileLoader\FileLoader;

	class WPThemeImage extends WPImage
	{
		public function __construct( string $src, array $attributes = [] )
		{
			if ( !isset( $attributes[ 'directory' ] ) || !$attributes[ 'directory' ] )
			{
				$attributes[ 'directory' ] = self::$default_shared_directory;
			}
			parent::__construct( $src, $attributes, new FileLoader([ 'directory-url' => get_stylesheet_directory_uri(), 'directory-server' => get_stylesheet_directory() ]) );
		}

		public static function setDefaultSharedDirectory( $directory )
		{
			self::$default_shared_directory = new Directory( $directory );
		}
		private static $default_shared_directory = null;
	}
}
