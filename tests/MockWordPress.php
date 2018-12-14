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

	const IMAGE_IDS =
	[
		[],
		[ 'src' => 'demo', 'ext' => 'png' ],
		[ 'src' => 'photo', 'ext' => 'jpg' ]
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

	function wp_get_attachment_image_src( $id, $size )
	{
		if ( $id < count( IMAGE_IDS ) )
		{
			return ( $size === 'full' )
			?
				[
					"https://www.example.com/wp-content/uploads/2018/12/" . IMAGE_IDS[ $id ][ 'src' ] . '.' . IMAGE_IDS[ $id ][ 'ext' ],
					2000,
					2000
				]
			:
				[
					"https://www.example.com/wp-content/uploads/2018/12/" . IMAGE_IDS[ $id ][ 'src' ] . "-" . WP_OPTIONS[ "{$size}_size_w" ] . "x" . WP_OPTIONS[ "{$size}_size_h" ] . '.' . IMAGE_IDS[ $id ][ 'ext' ],
					WP_OPTIONS[ "{$size}_size_w" ],
					WP_OPTIONS[ "{$size}_size_h" ]
				];
		}
		return null;
	}

	global $settings_fields;
	$settings_fields = [];

	function __( $name )
	{
		return $name;
	}

	function add_action( $hook, $function )
	{
		$function();
	}

	function add_settings_field( $slug, $name, $renderer, $group )
	{
		global $settings_fields;
		if ( !isset( $settings_fields[ $group ] ) )
		{
			$settings_fields[ $group ] = [];
		}
		$settings_fields[ $group ][] = $renderer;
	};

	function do_settings_sections( $group )
	{
		global $settings_fields;
		if ( isset( $settings_fields[ $group ] ) )
		{
			foreach ( $settings_fields[ $group ] as $renderer )
			{
				echo $renderer();
			}
		}
	};

	function add_theme_page() {};
	function add_option() {};
	function register_setting() {};
	function add_settings_section() {};
	function settings_errors() {};
	function settings_fields() {};
	function submit_button() { echo '<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">'; };
