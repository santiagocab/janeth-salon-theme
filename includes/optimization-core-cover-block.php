<?php
/**
 * Core Cover Block Optimization
 *
 * Optimizes the core cover block by preloading video assets.
 *
 * @package JanethSalon
 */

add_action( 'wp_head', 'blockstarter_preload_cover_video' );

/**
 * Preloads video assets from the first cover block on singular pages.
 *
 * Checks if the first block on a singular page is a core/cover block with video background
 * and adds a preload link tag to improve performance.
 *
 * @since 1.0.0
 */
function blockstarter_preload_cover_video() {

	if ( ! is_singular() ) {
		return;
	}

	global $post;

	if ( ! has_blocks( $post->post_content ) ) {
		return;
	}

	$blocks      = parse_blocks( $post->post_content );
	$first_block = $blocks[0] ?? null;
	if ( ! $first_block || 'core/cover' !== $first_block['blockName'] ) {
		return;
	}

	$attrs = $first_block['attrs'] ?? array();

	if ( isset( $attrs['backgroundType'] ) && 'video' === $attrs['backgroundType'] && ! empty( $attrs['url'] ) ) {
		$video_url = esc_url( $attrs['url'] );
		echo '<link rel="preload" as="video" href="' . esc_attr( $video_url ) . '" />';
	}
}
