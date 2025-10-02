<?php
/**
 * Custom Block Styles
 *
 * @package JanethSalon
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Custom_Block_Styles
 *
 * Handles registration of custom block styles for the theme.
 */
class Custom_Block_Styles {

	/**
	 * Constructor.
	 *
	 * Hooks into WordPress to register custom block styles.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_custom_block_styles' ) );
	}

	/**
	 * Register custom block styles.
	 *
	 * Registers custom styles for WordPress core blocks.
	 */
	public function register_custom_block_styles() {
		register_block_style(
			'core/social-links', // The block name (e.g., 'core/list' for the List block).
			array(
				'name'         => 'filled-square',
				'label'        => __( 'Filled Square', 'blockstarter' ),
				'inline_style' => '.wp-block-social-links.is-style-filled-square{gap:0.9rem;}.wp-block-social-links.is-style-filled-square li{ border-radius:0px;background-color:#000;}.wp-block-social-links.is-style-filled-square li svg{width:18px;height:18px;}}',
			)
		);
	}
}

new Custom_Block_Styles();
