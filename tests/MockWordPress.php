<?php
	const WP_OPTIONS =
	[
		'thumbnail_size_w' => '150',
		'thumbnail_size_h' => '150',
		'medium_size_w' => '300',
		'medium_size_h' => '300',
		'medium_large_size_w' => '768',
		'medium_large_size_h' => '768',
		'large_size_w' => '1024',
		'large_size_h' => '1024'
	];

	function get_intermediate_image_sizes()
	{
		return [ 'thumbnail', 'medium', 'medium_large', 'large' ];
	}

	function get_option( $option )
	{
		return WP_OPTIONS[ $option ];
	}

	function get_stylesheet_directory_uri()
	{
		return 'https://www.example.com/wp-content/themes/example';
	}

	function get_stylesheet_directory()
	{
		return getcwd();
	}

	function wp_upload_dir()
	{
		return
		[
			'path' => getcwd(),
			'url' => 'https://www.example.com/wp-content/uploads'
		];
	}

	function add_shortcode( $name, $action )
	{
		global $shortcodes;
		$shortcodes[ $name ] = $action;
	}

	function do_shortcode( $type, $atts )
	{
		global $shortcodes;
		return $shortcodes[ $type ]( $atts );
	}

	global $shortcodes;
	$shortcodes = [];
