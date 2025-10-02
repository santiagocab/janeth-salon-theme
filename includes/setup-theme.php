<?php
/**
 * Blockstarter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JanethSalon
 */

add_action( 'after_setup_theme', 'blockstarter_setup' );
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since 1.0.0
 */
function blockstarter_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'blockstarter', get_template_directory() . '/languages' );
		// Add theme support.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'html5', array( 'comment-form', 'comment-list' ) );
		add_theme_support( 'responsive-embeds' );
}

add_filter( 'get_custom_logo', 'blockstarter_get_custom_logo_callback' );
/**
 * Customizes the custom logo output.
 *
 * If no custom logo is set, returns a default logo from the theme's assets.
 *
 * @since 1.0.0
 *
 * @param string $html Custom logo HTML output.
 * @return string Modified custom logo HTML.
 */
function blockstarter_get_custom_logo_callback( $html ) {
	if ( has_custom_logo() ) {
		return $html;
	}
	$home_url  = esc_url( home_url( '/' ) );
	$svg_path  = esc_url( get_template_directory_uri() . '/assets/images/logo.svg' );
	$site_name = esc_attr( get_bloginfo( 'name' ) );
	return '<a href="' . $home_url . '" class="custom-logo-link" rel="home"><img width="294" height="157" class="custom-logo default" src="' . $svg_path . '" alt="' . $site_name . '"></a>';
}

add_filter(
	'upload_mimes',
	function ( $mimes ) {
		if ( current_user_can( 'manage_options' ) ) {
			$mimes['svg'] = 'image/svg+xml';
		}
		return $mimes;
	}
);

add_filter( 'excerpt_length', 'blockstarter_excerpt_length' );
/**
 * Filters the number of words in an excerpt.
 *
 * @since 1.0.0
 *
 * @param int $length The number of words.
 * @return int The modified excerpt length.
 */
function blockstarter_excerpt_length( $length ) {
	$length = 52;
	return $length;
}

add_action( 'after_setup_theme', 'register_images_sizes_on_activation' );
/**
 * Registers custom image sizes on theme activation.
 *
 * Sets up custom image sizes based on the theme's layout settings and
 * only runs once when the theme is first activated.
 *
 * @since 1.0.0
 */
function register_images_sizes_on_activation() {

	// If the theme isn't being activated for the first time abort.
	if ( get_option( 'janethsalon_theme_activation_flag' ) === '1' ) {
		return;
	}

	$settings = wp_get_global_settings();

	$content_width  = $settings['layout']['contentSize'] ?? '960px';
	$content_width  = apply_filters( 'tpd_content_width', $content_width );
	$content_height = ceil( ( $content_width * 9 ) / 16 );

	$container_width  = $settings['layout']['wideSize'] ?? '1240px';
	$container_width  = apply_filters( 'tpd_container_width', $container_width );
	$container_height = ceil( ( $container_width * 9 ) / 16 );

	// thumbnail size = 430px wide (no crop).
	add_image_size( 'thumbnail', 430, 0, true );
	update_option( 'thumbnail_size_w', 430 );
	update_option( 'thumbnail_size_h', 0 );

	// medium size = content width, and 16x9 aspect ratio (hard crop).
	add_image_size( 'medium', $content_width, $content_height, true );
	update_option( 'medium_size_w', $content_width );
	update_option( 'medium_size_h', $content_height );
	update_option( 'medium_crop', 1 );

	// medium large size = content width (no crop).
	add_image_size( 'medium_large', $content_width, 0, true );
	update_option( 'medium_large_size_w', $content_width );
	update_option( 'medium_large_size_h', 0 );

	// large size = 1440px wide (no crop).
	add_image_size( 'large', $content_width, $content_height, true );
	update_option( 'large_size_w', $content_width );
	update_option( 'large_size_h', $content_height );
	update_option( 'large_crop', 1 );

	// Set the theme activation flag to prevent the code from running again.
	update_option( 'janethsalon_theme_activation_flag', '1' );

	// Archive thumb size.
	add_image_size( 'archive-thumbnail-mobile', 167, 125, true );
}


add_filter( 'render_block_core/query-pagination-next', 'customize_pagination_next', 10, 1 );
/**
 * Customizes the next html output.
 *
 * @param string $block_content Block HTML output.
 * @return string Modified HTML.
 */
function customize_pagination_next( $block_content ) {
	$svg           = '<svg width="14px" height="14px" viewBox="-19.04 0 75.804 75.804" xmlns="http://www.w3.org/2000/svg">
  <g id="Group_65" data-name="Group 65" transform="translate(-831.568 -384.448)">
    <path id="Path_57" data-name="Path 57" d="M833.068,460.252a1.5,1.5,0,0,1-1.061-2.561l33.557-33.56a2.53,2.53,0,0,0,0-3.564l-33.557-33.558a1.5,1.5,0,0,1,2.122-2.121l33.556,33.558a5.53,5.53,0,0,1,0,7.807l-33.557,33.56A1.5,1.5,0,0,1,833.068,460.252Z" fill="#000000"/>
  </g>
</svg>';
	$block_content = str_replace( 'Next Page', $svg, $block_content );
	return $block_content;
}


add_filter( 'render_block_core/query-pagination-previous', 'customize_pagination_previous', 10, 1 );
/**
 * Customizes the previous html output.
 *
 * @param string $block_content Block HTML output.
 * @return string Modified HTML.
 */
function customize_pagination_previous( $block_content ) {
	$svg           = '<svg width="14px" height="14px" viewBox="-19.04 0 75.803 75.803" xmlns="http://www.w3.org/2000/svg">
  <g id="Group_64" data-name="Group 64" transform="translate(-624.082 -383.588)">
    <path id="Path_56" data-name="Path 56" d="M660.313,383.588a1.5,1.5,0,0,1,1.06,2.561l-33.556,33.56a2.528,2.528,0,0,0,0,3.564l33.556,33.558a1.5,1.5,0,0,1-2.121,2.121L625.7,425.394a5.527,5.527,0,0,1,0-7.807l33.556-33.559A1.5,1.5,0,0,1,660.313,383.588Z" fill="#0c2c67"/>
  </g>
</svg>';
	$block_content = str_replace( 'Previous Page', $svg, $block_content );
	return $block_content;
}


add_filter( 'render_block_core/post-date', 'post_date_linebreaks_regex', 10, 1 );
/**
 * Replace spaces with <br/> for the rendered core/post-date block when viewing single post.
 *
 * @param string $block_content Block HTML output.
 * @return string Modified HTML.
 */
function post_date_linebreaks_regex( $block_content ) {

	if ( is_single() || is_search() ) {
		// Regex to find the <a> tag and its content.
		// It captures the parts of the text to be reassembled with a line break.
		// Matches exactly 3 letters, followed by a space, followed by numbers only. This so only affects the date format like "Sep 23".
		$pattern = '/(<a[^>]*>)([A-Za-z]{3})\s(\d+)(<\/a>)/i';

		// Check if the pattern is found.
		if ( preg_match( $pattern, $block_content ) ) {
			// Replace the space with a <br/> tag.
			// $1 is the opening <a> tag.
			// $2 is the first part of the text (e.g., "Sep").
			// $3 is the second part of the text (e.g., "23").
			// $4 is the closing </a> tag.
			$block_content = preg_replace( $pattern, '$1$2<br/>$3$4', $block_content );
		}
	}

	return $block_content;
}


add_action( 'init', 'remove_emojis' );
/**
 * Removes emoji detection script and styles from the frontend.
 *
 * Improves performance by removing WordPress's default emoji support
 * scripts and styles when not needed on the frontend.
 *
 * @since 1.0.0
 */
function remove_emojis() {
	if ( is_admin() ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
