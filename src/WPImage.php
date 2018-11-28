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
			return ( $this->show_version )
				? new HTMLImage( $this->loader->getSourceWithVersion( $this->src ), $this->attributes )
				: new HTMLImage( $this->loader->getSource( $this->src ), $this->attributes );
		}

		public function getHTMLString() : string
		{
			return ( string )( $this->getHTML() );
		}

		private $src;
		private $loader;
		private $show_version;
		private $attributes;
	}
}
