<?php

declare( strict_types = 1 );
namespace WaughJ\WPImage
{
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\FileLoader\FileLoader;

	abstract class WPImage
	{
		public function __construct( string $src, array $attributes, FileLoader $loader )
		{
			$this->src = $src;
			$this->loader = ( isset( $attributes[ 'directory' ] ) && $attributes[ 'directory' ] ) ? $loader->changeSharedDirectory( $attributes[ 'directory' ] ) : $loader;
			$this->show_version = ( !isset( $attributes[ 'show-version' ] ) || $attributes[ 'show-version' ] );
			$this->attributes = $attributes;
			unset( $this->attributes[ 'show-version' ], $this->attributes[ 'directory' ] );
			if ( isset( $this->attributes[ 'srcset' ] ) && is_string( $this->attributes[ 'srcset' ] ) )
			{
				$this->attributes[ 'srcset' ] = $this->adjustSrcSet( $this->attributes[ 'srcset' ] );
			}
		}

		final public function __toString()
		{
			return $this->getHTMLString();
		}

		final public function print() : void
		{
			echo $this;
		}

		public function getHTML() : HTMLImage
		{
			return new HTMLImage( $this->getASource( $this->src ), $this->attributes );
		}

		public function getHTMLString() : string
		{
			return ( string )( $this->getHTML() );
		}

		private function adjustSrcSet( $srcset ) : string
		{
			$accepted_sources = [];
			$sources = preg_split( "/,[\s]*/", $srcset );
			foreach ( $sources as $source )
			{
				$parts = explode( ' ', $source );
				$width = $parts[ count( $parts ) - 1 ];
				array_pop( $parts );
				$filename = $this->getASource( implode( '', $parts ) );
				array_push( $accepted_sources, "$filename $width" );
			}
			return implode( ', ', $accepted_sources );
		}

		private function getASource( $src ) : string
		{
			return ( $this->show_version )
				? $this->loader->getSourceWithVersion( $src )
				: $this->loader->getSource( $src );
		}

		private $src;
		private $loader;
		private $show_version;
		private $attributes;
	}
}
