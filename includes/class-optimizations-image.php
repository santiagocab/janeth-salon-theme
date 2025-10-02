<?php
/**
 * Optimze Images, Gallery Block and Image Block and featured images.
 *
 * @package JanethSalon
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Images optimization class.
 *
 * Handles image sizes in gallery and image blocks depending on the context they are being used.
 *
 * @since 1.0.0
 */
class Optimizations_Image {

	/**
	 * Constructor.
	 *
	 * Initializes block filters.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'render_block_core/gallery', array( $this, 'gallery_block_rmv_extra_sizes' ), 10, 2 );
		add_filter( 'render_block_core/image', array( $this, 'image_block_rmv_extra_sizes' ), 10, 2 );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'set_post_image_srcset' ), 10, 5 );
		add_filter( 'wp_calculate_image_sizes', array( $this, 'set_post_image_sizes' ), 10, 5 );
	}

	/**
	 * Optimize Image Block Images
	 *
	 * @param string $html String of block html.
	 * @param string $size_slug String of the selected image size.
	 * @return string Modified String of block html.
	 */
	public function filter_img_tag_with_size_slug( $html, $size_slug ) {

		$p = new WP_HTML_Tag_Processor( $html );

		while ( $p->next_tag( 'img' ) ) {
			$attachment_id = $p->get_attribute( 'data-id' );
			if ( ! $attachment_id ) {
				continue;
			}

			$image_meta = wp_get_attachment_metadata( $attachment_id );
			if ( ! $image_meta || empty( $image_meta['sizes'][ $size_slug ] ) ) {
				continue;
			}

			$max_width  = $image_meta['sizes'][ $size_slug ]['width'];
			$max_height = $image_meta['sizes'][ $size_slug ]['height'];

			$uploads_dir = wp_get_upload_dir();
			$candidates  = array();

			foreach ( $image_meta['sizes'] as $name => $data ) {
				if ( $data['width'] <= $max_width && $data['height'] <= $max_height ) {
					$candidates[ $data['width'] ] = $uploads_dir['baseurl'] . '/' . dirname( $image_meta['file'] ) . '/' . $data['file'] . ' ' . $data['width'] . 'w';
				}
			}

			if ( $image_meta['width'] <= $max_width && $image_meta['height'] <= $max_height ) {
				$candidates[ $image_meta['width'] ] = wp_get_attachment_url( $attachment_id ) . ' ' . $image_meta['width'] . 'w';
			}

			if ( $candidates ) {
				ksort( $candidates );
				$srcset = implode( ', ', $candidates );
				$src    = wp_get_attachment_image_url( $attachment_id, $size_slug );

				$p->set_attribute( 'src', $src );
				$p->set_attribute( 'srcset', $srcset );
				$p->set_attribute( 'sizes', '100vw' );
			}
		}

		return $p->get_updated_html();
	}

	/**
	 * Remove extra sizes from gallery block images.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 * @return string Modified block content.
	 */
	public function gallery_block_rmv_extra_sizes( $block_content, $block ) {
		if ( empty( $block['attrs']['sizeSlug'] ) ) {
			return $block_content;
		}
		return $this->filter_img_tag_with_size_slug( $block_content, $block['attrs']['sizeSlug'] );
	}

	/**
	 * Remove extra sizes from image block images.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 * @return string Modified block content.
	 */
	public function image_block_rmv_extra_sizes( $block_content, $block ) {
		if ( empty( $block['attrs']['sizeSlug'] ) ) {
			return $block_content;
		}
		// remove images bigger than selected size for this image block.
		$new_block_content = $this->filter_img_tag_with_size_slug( $block_content, $block['attrs']['sizeSlug'] );
		return $new_block_content;
	}

	/**
	 * Set optimized srcset for post images.
	 *
	 * Filters the calculated srcset for images to optimize performance by removing
	 * larger sizes than necessary and ensuring appropriate image sizes are included.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $sources     One or more arrays of source data to include in the 'srcset'.
	 * @param array  $size_array  Array of width and height values in pixels (in that order).
	 * @param string $image_src   The 'src' of the image.
	 * @param array  $image_meta  The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param int    $attachment_id Image attachment ID.
	 * @return array Modified sources array for srcset.
	 */
	public function set_post_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {

		$settings = wp_get_global_settings();

		$content_width  = $settings['layout']['contentSize'] ?? '960px';
		$content_width  = apply_filters( 'tpd_content_width', $content_width );
		$content_width  = intval( substr( $content_width, 0, -2 ) ); // remove 'px' and convert to int.
		$content_height = ceil( ( $content_width * 9 ) / 16 );

		$container_width  = $settings['layout']['wideSize'] ?? '1240px';
		$container_width  = apply_filters( 'tpd_container_width', $container_width );
		$container_width  = intval( substr( $container_width, 0, -2 ) ); // remove 'px' and convert to int.
		$container_height = ceil( ( $container_width * 9 ) / 16 );

		// avoid recomputing or re-fetching.
		static $thumbnail_option_width = null;

		if ( ! is_single() || ! is_main_query() ) {
			return $sources;
		}

		// sort keys(image widths) desc order.
		krsort( $sources, SORT_NUMERIC );

		if ( ! $content_width ) {
			$content_width = get_content_width() ?? 788;
		}

		if ( ! $thumbnail_option_width ) {
			$thumbnail_option_width = (int) get_option( 'thumbnail_size_w' ) ?? 428;
		}

		// confirm there is a perfect match for medium width among the image options otherwise grab the next closest img.
		foreach ( $sources as $width => $value ) {
			if ( $width >= $content_width ) {
				$closest_image_to_medium_size = $width;
			}
		}
		// if $closest_image_to_medium_size is set then remove all images bigger than that.
		if ( isset( $closest_image_to_medium_size ) && ! empty( $closest_image_to_medium_size ) ) {
			$content_width = $closest_image_to_medium_size;
			foreach ( $sources as $width => $value ) {
				if ( $width > $content_width ) {
					unset( $sources[ $width ] );
				}
			}
		}

		// as keys are ordered this gets the biggest key aka biggest img.
		$largest_image = reset( $sources );

		// Find thumbnail or closest size.
		$thumbnail_width = null;
		if ( isset( $image_meta['sizes']['thumbnail'] ) ) {
			$thumbnail_width = $image_meta['sizes']['thumbnail']['width'];
		} else {
			// Find closest width to the expected thumbnail width.
			$target_width = $thumbnail_option_width;
			$closest_diff = PHP_INT_MAX;
			foreach ( $image_meta['sizes'] as $size ) {
				$width = $size['width'];
				$diff  = abs( $width - $target_width );
				if ( $diff < $closest_diff ) {
					$closest_diff    = $diff;
					$thumbnail_width = $width;
				}
			}
		}
		$thumbnail_image = $thumbnail_width && isset( $sources[ $thumbnail_width ] ) ? $sources[ $thumbnail_width ] : null;

		if ( ! $thumbnail_image ) {
			$thumb_url       = wp_get_attachment_thumb_url( $attachment_id );
			$thumbnail_image = array(
				'url'        => $thumb_url,
				'descriptor' => 'w',
				'value'      => $thumbnail_option_width,
			);
		}

		if ( $thumbnail_image ) {
			$sources[ $thumbnail_option_width ] = $thumbnail_image;
		}

		return $sources;
	}

	/**
	 * Set optimized sizes attribute for post images.
	 *
	 * Filters the 'sizes' attribute for images to provide appropriate sizing hints
	 * for responsive images, optimizing for single post context.
	 *
	 * @since 1.0.0
	 *
	 * @param string $sizes       A source size value for use in a 'sizes' attribute.
	 * @param array  $size        Requested size. Array of width and height values in pixels (in that order).
	 * @param string $image_src   The URL to the image.
	 * @param array  $image_meta  The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param int    $attachment_id Image attachment ID.
	 * @return string Modified sizes attribute value.
	 */
	public function set_post_image_sizes( $sizes, $size, $image_src, $image_meta, $attachment_id ) {

		static $featured_image_found = false;
		static $logo_id              = '';

		if ( ! is_single() || ! is_main_query() ) {
			return $sizes; }

		$logo_id = ( empty( $logo_id ) ) ? get_theme_mod( 'custom_logo' ) : $logo_id;

		// don't modify logo image sizes.
		if ( $attachment_id === $logo_id ) {
			return $sizes; }

		if ( ! $featured_image_found ) {
			$featured_image_id = get_post_thumbnail_id();

			if ( $attachment_id === $featured_image_id ) {
				$featured_image_found = true;
				return $sizes;
			}
		}

		return '(max-width: 430px) 100px, 100vw';
	}
}

new Optimizations_Image();
