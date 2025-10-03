<?php
/**
 * Title: Archive Header Image
 * Slug: janeth-salon-theme/archive-header-image
 * Categories: janeth-salon-theme
 *
 * @package JanethSalon
 * Inserter: no
 */

?>

<!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-archive.webp","dimRatio":40,"overlayColor":"header-image-overlay","minHeightUnit":"vh","align":"full","className":"is-light hero-image"} -->
	<div class="wp-block-cover alignfull is-light hero-image">
		<span aria-hidden="true" class="wp-block-cover__background has-header-image-overlay-background-color has-background-dim-40 has-background-dim"></span>
		<img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-archive.webp" data-object-fit="cover"/>
		<div class="wp-block-cover__inner-container">
			<!-- wp:query-title {"type":"archive","textColor":"base","textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} /-->
			<!-- wp:term-description {"textColor":"base","textAlign":"center"} /-->
		</div>
	</div>
<!-- /wp:cover -->