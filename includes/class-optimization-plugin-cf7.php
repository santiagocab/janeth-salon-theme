<?php
/**
 * Run optimizations for the "Contact Form 7" plugin
 * because it loads its assets where it doesn't need to.
 *
 * @package JanethSalon
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Optimization_Plugin_Cf7
 *
 * Handles optimizations for Contact Form 7 plugin by conditionally loading assets.
 */
class Optimization_Plugin_Cf7 {

	/**
	 * Constructor.
	 *
	 * Sets up hooks for Contact Form 7 optimization.
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_contact_form_assets_if_unneeded' ), 11 );
		add_filter( 'critical_base_css', array( $this, 'add_cf7_css' ), 10, 1 );
	}

	/**
	 * Check the current page / post's content for one of the following:
	 *  - Shortcode: contact-form-7
	 *  - Shortcode: contact-form
	 *  - Block Type: contact-form-7/contact-form-selector
	 * If none are found then dequeue all the plugin's js and css assets.
	 */
	public function dequeue_contact_form_assets_if_unneeded() {

		if ( is_admin() ) {
			return;
		}

		global $post;

		// Safety checks.
		if ( ! $post || ! property_exists( $post, 'post_content' ) ) {
			return;
		}

		// Check if content has the contact form 7 block.
		if ( has_block( 'contact-form-7/contact-form-selector', $post ) ) {
			return;
		}

		// Check if content has a contact form shortcode.
		$post_content = $post->post_content;
		if ( has_shortcode( $post_content, 'contact-form-7' ) || has_shortcode( $post_content, 'contact-form' ) ) {
			return;
		}

		// Remove assets since content doesn't need it.
		wp_dequeue_script( 'contact-form-7' );
		wp_dequeue_script( 'swv' );
		wp_dequeue_style( 'contact-form-7' );
	}

	/**
	 * Add Contact Form 7 critical CSS to the base CSS.
	 *
	 * @param string $css The existing CSS content.
	 * @return string The CSS content with Contact Form 7 styles added if needed.
	 */
	public function add_cf7_css( $css ) {

		if ( is_admin() ) {
			return $css;
		}

		global $post;

		// Safety checks.
		if ( ! $post || ! property_exists( $post, 'post_content' ) ) {
			return $css;
		}

		// Check if content has a contact form.
		$post_content = $post->post_content;
		if ( ! has_block( 'contact-form-7/contact-form-selector', $post ) && ! has_shortcode( $post_content, 'contact-form-7' ) && ! has_shortcode( $post_content, 'contact-form' ) ) {
			return $css;
		}

		// Plugin: Contact Form 7 CSS.
		if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {

			$critical_css_path = get_template_directory() . '/dist/css/plugin-cf7.min.css';
			if ( file_exists( $critical_css_path ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				$css .= file_get_contents( $critical_css_path );
			}
		}

		return $css;
	}
}

new Optimization_Plugin_Cf7();
