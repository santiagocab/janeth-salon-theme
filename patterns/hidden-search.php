<?php
/**
 * Title: Search
 * Slug: janeth-salon-theme/search
 * Categories: janeth-salon-theme
 * Inserter: no
 *
 * @package JanethSalon
 */

?>

<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","right":"var:preset|spacing|x-small","left":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--x-small);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--x-small)">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|medium"}}} -->
			<div class="wp-block-group">
				<!-- wp:query-title {"type":"search","textAlign":"center"} /-->
				<!-- wp:search {"label":"","placeholder":"<?php esc_html_e( 'Search site...', 'janeth-salon-theme' ); ?>","width":50,"widthUnit":"%","buttonText":"<?php esc_html_e( 'Search', 'janeth-salon-theme' ); ?>","buttonUseIcon":true,"align":"center"} /-->
			</div>
		<!-- /wp:group -->
	</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0px","bottom":"var:preset|spacing|large","right":"var:preset|spacing|x-small","left":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} --> 
	<div class="wp-block-group alignfull" style="padding-top:0px;padding-right:var(--wp--preset--spacing--x-small);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--x-small)">
		<!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"tagName":"main","align":"full","className":"is-style-left-featured-image","layout":{"type":"constrained"}} -->
			<main class="wp-block-query alignfull is-style-left-featured-image">
				<!-- wp:post-template -->
					<!-- wp:group {"className":"post-header","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
						<div class="wp-block-group post-header" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
							<!-- wp:post-featured-image {"isLink":true,"height":""} /-->
							<!-- wp:group {"style":{"typography":{"fontSize":"var:preset|font-size|xx-small"},"spacing":{"padding":{"top":"var:preset|spacing|x-small"}}},"layout":{"type":"flex"}} -->
							<div class="wp-block-group" style="font-size:var(--wp--preset--font-size--xx-small);padding-top:var(--wp--preset--spacing--x-small)">
								<!-- wp:post-author {"showAvatar":false,"isLink":true,"showBio":false,"fontFamily":"kanit","style":{"typography":{"textTransform":"uppercase","letterSpacing":"4px"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}}} /-->
								<!-- wp:post-date {"format":"M j","isLink":true} /-->
								<!-- wp:post-terms {"term":"category","style":{"typography":{"textTransform":"uppercase","letterSpacing":"4px"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontFamily":"kanit"} /-->
							</div>
							<!-- /wp:group -->
							<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|xx-small"}}}} -->
								<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--xx-small)">
									<!-- wp:post-title {"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|footer"}}},"typography":{"textTransform":"uppercase"}},"textColor":"black","fontSize":"xxx-large"} /-->
									<!-- wp:post-excerpt {"showMoreOnNewLine":false} /-->
									<!-- wp:spacer {"height":"10px"} -->
										<div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
									<!-- /wp:spacer -->
									<!-- wp:read-more {"fontFamily":"kanit","style":{"typography":{"textDecoration":"underline","textTransform":"uppercase","letterSpacing":"1px"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontSize":"xx-small"} /-->
								</div>
							<!-- /wp:group -->
						</div>
					<!-- /wp:group -->
				<!-- /wp:post-template -->
				<!-- wp:group {"layout":{"inherit":true,"type":"constrained"}} -->
					<div class="wp-block-group">
						<!-- wp:query-pagination {"className":"is-style-pagination-button","layout":{"type":"flex","justifyContent":"center"}} -->
							<!-- wp:query-pagination-previous /-->
							<!-- wp:query-pagination-numbers /-->
							<!-- wp:query-pagination-next /-->
						<!-- /wp:query-pagination -->
					</div>
				<!-- /wp:group -->
				<!-- wp:query-no-results -->
					<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results.","style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
						<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><?php esc_html_e( 'Sorry, nothing was found for that search term.', 'janeth-salon-theme' ); ?></p>
					<!-- /wp:paragraph -->
				<!-- /wp:query-no-results -->
			</main>
		<!-- /wp:query -->
	</div>
<!-- /wp:group -->
