<?php

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
