<?php
/**
 * Title: Header Image
 * Slug: blockstarter/header-image
 * Categories: blockstarter
 *
 * @package JanethSalon
 */

?>

<!-- wp:cover {"useFeaturedImage":true,"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-image.webp","dimRatio":40,"overlayColor":"header-image-overlay","minHeightUnit":"vh","isDark":false,"align":"full","className":"hero-image"} -->
	<div class="wp-block-cover alignfull is-light hero-image">
		<span aria-hidden="true" class="wp-block-cover__background has-header-image-overlay-background-color has-background-dim-40 has-background-dim"></span>
		<img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-image.webp" data-object-fit="cover"/>
		<div class="wp-block-cover__inner-container">
			<!-- wp:post-title {"level":1,"textAlign":"center","style":{"typography":{"fontSize":"64px"},"spacing":{"margin":{"bottom":"var:preset|spacing|xx-small"}}},"textColor":"white"} /-->
		</div>
	</div>
<!-- /wp:cover -->

<!-- wp:spacer {"height":"60px"} -->
	<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->