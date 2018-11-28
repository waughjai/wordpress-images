=== WAJ Image ===
Contributors: waughjai
Tags: image, loader, html generator
Requires at least: 4.9.8
Tested up to: 4.9.8
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simple classes & shortcodes for easy image HTML generation from common image directories.


== Description ==

This plugin has 3 main classes, with a shortcode for each 1.

Shorcodes:
* [image src="%url%"]
* [theme-image src="%url%"]
* [upload-image src="%url%"]

Classes:
* WaughJ\HTMLImage\HTMLImage
* WaughJ\WPImage\WPThemeImage
* WaughJ\WPImage\WPUploadsImage

The regular image shortcode loads the exact source URL given, without a cache-corruption-breaking version parameter. It is mainly for use by other classes, like the next 2.

The theme image shortcode loads an image from the current theme directory.

The upload image shortcode loads an image from the uploads directory.

All 3 have near the same interface: a mandatory source & optional arguments. For the shortcodes, this means a "src" attribute as well as any other valid HTML attributes; for the classes, it means a hash map as an optional 2nd argument.

In addition to any valid HTML attributes, the WPThemeImage and WPUploadsImage classes also accept "directory" & "show-version" attributes. The former, if set, will automatically put the source in the given directory; the "show-version" attribute, if set to false, won't try to find the image's last modified type to give it a version parameter for breaking cache corruption.

If "alt" attribute is not set, an empty 1 will automatically be added to the HTML generated, ensuring that all images made through these will have an alt tag.

To make working with theme image objects with minimal inconvenience for images that are all in the same directory that is not the topmost directory o' the theme directory, you can globally set the inner shared directory using WPThemeImage's static setDefaultSharedDirectory method on the class itself. After that, all initialized WPThemeImage instances, including the shortcode, will automatically use that shared directory if a different 1 isn't provided.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Images can be added using shortcode in WordPress editors or directly in PHP by using instances o' classes. Instances o' classes can be automatically casted into strings & used as strings.


== Examples ==

use WaughJ\WPImage\WPUploadImage;
echo new WPUploadImage( 'demo.png', [ 'directory' => '2018/12', 'class' => 'center-img portrait', 'width' => 800, 'height' => 600, 'alt' => 'King' ] );

Will generate `<img class="center-img portrait" width="800" height="600" alt="King" src="https://www.domain.com/wp-content/uploads/2018/12/demo.png?m=#######" />`

use WaughJ\WPImage\WWPThemeImage;
WPThemeImage::setDefaultSharedDirectory( 'img' );
echo new WPThemeImage( 'photo.jpg' );

Will generate `<img src="https://www.domain.com/wp-content/themes/theme-slug/img/photo.jpg?m=#########" alt="" />`


== Changelog ==

= 1.0 =
* Initial stable version.
