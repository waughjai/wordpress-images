# WAJ Image
* Contributors: waughjai
* Tags: image, loader, html generator
* Requires at least: 5.0.0
* Tested up to: 5.1.1
* Stable tag: 2.1.1
* Requires PHP: 7.0
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simple classes & shortcodes for easy image HTML generation from common image directories.


## Description

This plugin has 6 main classes, with a shortcode for each 1.

Shorcodes:
* [image src="%url%"]
* [theme-image src="%url%"]
* [upload-image src="%url%"]
* [picture src="%baseurl%" ext="%ext%" sizes="%sizes%"]
* [theme-picture src="%baseurl%" ext="%ext%" sizes="%sizes%"]
* [upload-picture src="%baseurl%" ext="%ext%" sizes="%sizes%"]

Classes:
* WaughJ\HTMLImage\HTMLImage
* WaughJ\WPThemeImage\WPThemeImage
* WaughJ\WPUploadImage\WPUploadImage
* WaughJ\HTMLPicture\HTMLPicture
* WaughJ\WPThemePicture\WPThemePicture
* WaughJ\WPUploadPicture\WPUploadPicture

The regular image shortcode loads the exact source URL given, without a cache-corruption-breaking version parameter. It is mainly for use by other classes, like the next 2.

The regular picture shortcode loads %base%.%ext%, as well as extra sources based on the sizes given. Read https://developer.mozilla.org/en-US/docs/Web/HTML/Element/picture for mo' info on how the picture element works.

The theme image & picture shortcodes load images from the current theme directory.

The upload image & picture shortcodes load images from the uploads directory.

The HTML & Theme classes have near the same interface: a mandatory source & optional arguments. For the shortcodes, this means a "src" attribute as well as any other valid HTML attributes; for the classes, it means a hash map as an optional 2nd argument.

The WPUploadImage class takes a mandatory ID integer representing the ID o' the image in the media section o' the WordPress admin, the 2nd argument is an optional size string representing the slug o' the size type as registered in WordPress, with "responsive" for automatically an image that uses srcset to dynamically load the size for different window sizes, & the optional 3rd argument is a hash map for extra attributes, as 'bove.

The WPUploadPicture takes a mandatory ID integer & the optional attributes hash map.

In addition to any valid HTML attributes, the WPThemeImage & WPThemePicture classes also accept "directory" & they & the uploads classes accept the "show-version" attributes. The former, if set, will automatically put the source in the given directory; the "show-version" attribute, if set to false, won't try to find the image's last modified type to give it a version parameter for breaking cache corruption.

If "alt" attribute is not set, an empty 1 will automatically be added to the HTML generated, ensuring that all images made through these will have an alt tag.

To make working with theme image objects with minimal inconvenience for images that are all in the same directory that is not the topmost directory o' the theme directory, you can globally set the inner shared directory in the WordPress admin through Appearances -> Theme -> Directories, or directly in PHP with WPThemeImage's static setDefaultSharedDirectory method on the class itself. After that, all initialized WPThemeImage & WPThemePicture instances, including the shortcodes, will automatically use that shared directory if a different 1 isn't provided.

To add HTML attributes to WPThemePicture & WPUploadPicture shortcodes, prefix them with "img-", "picture-", or "source-" depending on what tag you want the attribute given to. For example, to apply a class to the img tag, give the shortcode the attribute "img-class".


## Installation

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Images can be added using shortcode in WordPress editors or directly in PHP by using instances o' classes. Instances o' classes can be automatically casted into strings & used as strings.


## Examples

	use WaughJ\WPUploadImage\WPUploadImage;
	echo new WPUploadImage
	(
		31,
		[
			'class' => 'center-img portrait',
			'alt' => 'King'
		]
	);

&

	[upload-image id="31" class="center-img portrait" alt="King"]

Will generate something like `<img class="center-img portrait" alt="King" src="https://www.domain.com/wp-content/uploads/2018/12/demo-150x150.png?m=1543875777" />`

	use WaughJ\WPThemeImage\WPThemeImage;
	WPThemeImage::setDefaultSharedDirectory( 'img' );
	echo new WPThemeImage( 'photo.jpg' );

Will generate something like `<img src="https://www.domain.com/wp-content/themes/theme-slug/img/photo.jpg?m=1543875777" alt="" />`

	[upload-picture id="8"]

Will generate something like `<picture><source srcset="https://www.example.com/wp-content/uploads/2018/12/photo-150x150.jpg?m=1543875777" media="(max-width:150px)"><source srcset="https://www.example.com/wp-content/uploads/2018/12/photo-300x300.jpg?m=1543875781" media="(max-width:300px)"><source srcset="https://www.example.com/wp-content/uploads/2018/12/photo-768x768.jpg?m=1543875785" media="(max-width:768px)"><source srcset="https://www.example.com/wp-content/uploads/2018/12/photo-1024x1024.jpg?m=1543875831"><img src="https://www.example.com/wp-content/uploads/2018/12/photo-150x150.jpg?m=1543875777" alt="" /></picture>`


## Changelog

### 2.1.1
* Fix WPThemePicture bug changing default theme shared directory.

### 2.1.0
* Update dependencies, test for WordPress 5.1.

### 2.0.1
* Fix buggy WPUploadImage class.

### 2.0
* Refactor into split classes.
* Fix WordPress Uploads incompatibility with WPUpload classes.

### 1.3
* Add directory bar to admin.

### 1.2
* Add ability to just get source from images.

### 1.1
* Make uploads classes mo' automatic.

### 1.0
* Initial stable version.
