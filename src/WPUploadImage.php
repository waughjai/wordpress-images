<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\FileLoader\FileLoader;
	use function WaughJ\WPGetImageSizes\WPGetImageSizes;

	class WPUploadImage extends HTMLImage
	{
		public function __construct( string $src, array $attributes = [] )
		{
			$loader = self::getFileLoader( $attributes );
			unset( $attributes[ 'directory' ] ); // Make sure we don't keep this is an attribute that gets passed into the HTML itself.
			if ( isset( $attributes[ 'srcset' ] ) && $attributes[ 'srcset' ] === 'auto' && isset( $attributes[ 'ext' ] ) )
			{
				$attributes = self::autoSrcSetAndSizes( $attributes, $src );
				$src .= '.' . $attributes[ 'ext' ];
				unset( $attributes[ 'ext' ] );
			}
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

		private static function autoSrcSetAndSizes( array $attributes, string $src ) : array
		{
			$image_sizes = WPGetImageSizes();
			$src_strings = [];
			$size_strings = [];
			$number_of_sizes = count( $image_sizes );
			for ( $i = 0; $i < $number_of_sizes; $i++ )
			{
				$size = $image_sizes[ $i ];
				$src_strings[] = "{$src}-{$size->getWidth()}x{$size->getHeight()}.{$attributes[ 'ext' ]} {$size->getWidth()}w";
				$size_strings[] = ( $i === $number_of_sizes - 1 )
					? "{$size->getWidth()}px"
					: "(max-width: {$size->getWidth()}px) {$size->getWidth()}px";
			}
			$attributes[ 'srcset' ] = implode( ', ', $src_strings );
			$attributes[ 'sizes' ] = implode( ', ', $size_strings );
			return $attributes;
		}
	}
}
