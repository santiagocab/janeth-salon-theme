<?php
/**
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

class Setup_Theme {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_support_init' ) );
		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
		add_action( 'after_setup_theme', array( $this, 'register_images_sizes_on_activation' ) );
		add_filter( 'get_custom_logo', array( $this, 'blockstarter_get_custom_logo_callback' ) );
	}

	public function theme_support_init() {
		// Add theme support
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'html5', array('comment-form', 'comment-list') );
		add_theme_support( 'responsive-embeds' );
	}


	public function excerpt_length(  $length  ) {
		return 25;
	}


	public function register_images_sizes_on_activation() {

		// If the theme isn't being activated for the first time abort.
		if ( get_option( 'blockstarter_theme_activation_flag' ) == '1' ) {
			return;
		}

		$settings = wp_get_global_settings();
				
		$content_width = $settings['layout']['contentSize'] ?? '960px';
		$content_height = ceil( ( $content_width * 9 ) / 16 );

		// Add filters for these values
		$content_width = apply_filters( 'blockstarter_content_width', $content_width );
		$content_height = apply_filters( 'blockstarter_content_height', $content_height );

		/**
		 * Override default image sizes
		 */

		// thumbnail size = 410px wide (no crop)
		add_image_size( 'thumbnail', 410, 0, true );
		update_option( 'thumbnail_size_w', 410 );
		update_option( 'thumbnail_size_h', 0 );

		// medium size = content width, and 16x9 aspect ratio (hard crop)
		add_image_size( 'medium', $content_width, $content_height, true );
		update_option( 'medium_size_w', $content_width );
		update_option( 'medium_size_h', $content_height );
		update_option( 'medium_crop', 1 );

		// medium large size = content width (no crop)
		add_image_size( 'medium_large', $content_width, 0, true );
		update_option( 'medium_large_size_w', $content_width );
		update_option( 'medium_large_size_h', 0 );

		// large size = 1440px wide (no crop)
		add_image_size( 'large', 1440, 0, true );
		update_option( 'large_size_w', 1440 );
		update_option( 'large_size_h', 0 );

		/**
		 * Custom image sizes
		 */

		// mini thumbnail size = 205px wide (no crop)
		add_image_size( 'mini-thumbnail', 205, 0, true );
				
		// Set the theme activation flag to prevent the code from running again.
		update_option( 'blockstarter_theme_activation_flag', '1' );

	}

	public function blockstarter_get_custom_logo_callback(  $html  ) {
		if (has_custom_logo()) {
			return $html;
		}
		$home_url = esc_url( home_url('/') );
		$svg_path = esc_url( get_template_directory_uri() . '/assets/images/logo.svg' );
		$site_name = esc_attr( get_bloginfo('name') );
		return '<a href="' . $home_url . '" class="custom-logo-link" rel="home"><img width="294" height="157" class="custom-logo default" src="' . $svg_path . '" alt="' . $site_name . '"></a>';
	}

}
new Setup_Theme();