<?php
/**
 * Custom Block Patterns Functionality
 *
 * @package janethsalon
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Block Patterns class.
 *
 * Handles registration of custom block patterns and pattern categories.
 *
 * @since 1.0.0
 */
class Custom_Block_Patterns {

	/**
	 * Constructor.
	 *
	 * Initializes the custom block patterns functionality by hooking into WordPress init action.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'blockstarter_register_block_patterns' ), 9 );
	}

	/**
	 * Registers block patterns categories, and type.
	 */
	public function blockstarter_register_block_patterns() {
		$block_pattern_categories = array(
			'blockstarter' => array(
				'label' => esc_html__( 'Blockstarter', 'blockstarter' ),
			),
		);
		$block_pattern_categories = apply_filters( 'blockstarter_block_pattern_categories', $block_pattern_categories );
		foreach ( $block_pattern_categories as $name => $properties ) {
			if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
				register_block_pattern_category( $name, $properties );
			}
		}
	}
}

new Custom_Block_Patterns();
