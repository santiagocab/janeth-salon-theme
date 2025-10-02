<?php
/**
 * Custom Archives Functionality
 *
 * @package JanethSalon
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Archives Functionality class.
 *
 * Handles custom archive functionality including category header images
 * and archive-specific styling.
 */
class Custom_Archives_Functionality {

	/**
	 * Constructor.
	 *
	 * Initialize hooks and filters for custom archives functionality.
	 */
	public function __construct() {
		add_action( 'category_edit_form_fields', array( $this, 'category_header_image_field' ) );
		add_action( 'edited_category', array( $this, 'save_category_header_image' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'category_header_image_field_script' ) );
		add_filter( 'render_block_core/cover', array( $this, 'use_term_meta_img_if_available' ), 10, 2 );
		add_filter( 'render_block_core/post-date', array( $this, 'archive_post_date_linebreaks_regex' ), 10, 3 );
	}

	/**
	 * Add custom header image field to category edit form.
	 *
	 * @param WP_Term $term The term object.
	 */
	public function category_header_image_field( $term ) {
		$header_image_id  = get_term_meta( $term->term_id, 'category_header_image', true );
		$header_image_url = '';

		if ( $header_image_id ) {
			$header_image_url = wp_get_attachment_image_url( $header_image_id, 'full' );
		}
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="category_header_image"><?php esc_html_e( 'Header Image', 'janethsalon' ); ?></label>
			</th>
			<td>
				<div class="category-header-image-wrapper">
					<input type="hidden" id="category_header_image" name="category_header_image" value="<?php echo esc_attr( $header_image_id ); ?>" />
					<button type="button" class="button category-header-image-upload" data-target="category_header_image">
						<?php esc_html_e( 'Select Header Image', 'janethsalon' ); ?>
					</button>
					<button type="button" class="button category-header-image-remove" style="<?php echo $header_image_id ? '' : 'display:none;'; ?>">
						<?php esc_html_e( 'Remove Image', 'janethsalon' ); ?>
					</button>
					<div class="category-header-image-preview" style="margin-top: 10px;">
						<?php if ( $header_image_url ) : ?>
							<img src="<?php echo esc_url( $header_image_url ); ?>" style="max-width: 300px; height: auto;" />
						<?php endif; ?>
					</div>
				</div>
				<p class="description"><?php esc_html_e( 'Upload a custom header image for this category.', 'janethsalon' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save category header image field.
	 *
	 * @param int $term_id The term ID.
	 */
	public function save_category_header_image( $term_id ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- WordPress core handles nonce verification for term updates.
		if ( isset( $_POST['category_header_image'] ) ) {
			$header_image_id = intval( $_POST['category_header_image'] );
			if ( $header_image_id > 0 ) {
				update_term_meta( $term_id, 'category_header_image', $header_image_id );
			} else {
				delete_term_meta( $term_id, 'category_header_image' );
			}
		}
	}

	/**
	 * Add media uploader scripts to category edit page.
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function category_header_image_field_script( $hook ) {
		// Debug: Check what page we're on.
		global $pagenow, $taxnow;

		// Load scripts on category edit pages.
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Just checking taxonomy parameter.
		if ( ( 'edit-tags.php' === $hook || 'term.php' === $hook ) &&
			( ( isset( $_GET['taxonomy'] ) && 'category' === $_GET['taxonomy'] ) || 'category' === $taxnow ) ) {

			wp_enqueue_media();
			wp_enqueue_script( 'category-header-image', get_template_directory_uri() . '/assets/js/category-header-image.js', array( 'jquery' ), '1.0.1', true );

			// Add some basic CSS styling.
			$css = '
			.category-header-image-wrapper {
				max-width: 400px;
			}
			.category-header-image-preview img {
				border: 1px solid #ddd;
				border-radius: 4px;
				padding: 5px;
			}
			.category-header-image-upload,
			.category-header-image-remove {
				margin-right: 10px;
			}
			.category-header-image-remove {
				background-color: #dc3232;
				border-color: #dc3232;
				color: #fff;
			}
			.category-header-image-remove:hover {
				background-color: #c32d2d;
				border-color: #c32d2d;
			}
			';
			wp_add_inline_style( 'wp-admin', $css );
			wp_enqueue_style( 'wp-admin' );
		}
	}

	/**
	 * Get category header image URL.
	 *
	 * @param int    $category_id Category ID.
	 * @param string $size Image size.
	 * @return string|false Image URL or false if not found.
	 */
	public function get_category_header_image( $category_id = null, $size = 'full' ) {
		if ( ! $category_id ) {
			$category_id = get_queried_object_id();
		}

		$header_image_id = get_term_meta( $category_id, 'category_header_image', true );

		if ( $header_image_id ) {
			return wp_get_attachment_image_url( $header_image_id, $size );
		}

		return false;
	}

	/**
	 * Use term meta image if available for hero image blocks.
	 *
	 * @param string $block_content Block HTML output.
	 * @param array  $block Block data.
	 * @return string Modified HTML.
	 */
	public function use_term_meta_img_if_available( $block_content, $block ) {
		if ( isset( $block['attrs']['className'] ) && str_contains( $block['attrs']['className'], 'hero-image' ) ) {
			$custom_img_src = $this->get_category_header_image( get_queried_object_id(), 'large' );
			if ( $custom_img_src ) {
				$default_img_src = 'http://janethsalon.local/wp-content/themes/janethsalon/assets/images/hero-archive.webp';
				$block_content   = str_replace( $default_img_src, $custom_img_src, $block_content );
			}
		}
		return $block_content;
	}

	/**
	 * Replace spaces with <br/> for the rendered core/post-date block when viewing archives.
	 *
	 * @param string   $block_content Block HTML output.
	 * @param array    $block         Block data.
	 * @param WP_Block $instance    Block instance (unused).
	 * @return string Modified HTML.
	 */
	public function archive_post_date_linebreaks_regex( $block_content, $block, $instance ) {
		// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- Required by WordPress filter hook.
		unset( $block, $instance );

		if ( is_archive() ) {
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
}

new Custom_Archives_Functionality();