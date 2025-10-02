<?php
/**
 * Register Stylesheets and Block Styles
 *
 * Optimizes how style assets are added to the site.
 *
 * @package janethsalon
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom_Enqueue_Styles class.
 */
class Custom_Enqueue_Styles {

	/**
	 * Constructor.
	 *
	 * Initializes the custom enqueue styles functionality by hooking into WordPress actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'body_class', array( $this, 'variation_body_classes' ) );
		add_action( 'init', array( $this, 'register_block_styles' ) );
		add_action( 'init', array( $this, 'enqueue_theme_styles' ) );
		add_action( 'wp_head', array( $this, 'critical_css' ), 999 );
		add_filter( 'critical_base_css', array( $this, 'templates_styles' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'theme_asset_css_vars' ) );
	}

	/**
	 * Add custom body class based on the style variation selected in global styles.
	 *
	 * @param array $classes Array of body classes.
	 * @return array Modified array of body classes.
	 */
	public function variation_body_classes( $classes ) {

			// Style variation name from global styles.
			$style_variation = wp_get_global_settings( array( 'custom', 'variation' ) );
		if ( 'default' !== $style_variation ) {
			$classes[] = 'variation-' . $style_variation;
		}

			return $classes;
	}

	/**
	 * Add block style variations.
	 */
	public function register_block_styles() {
			$block_styles = array(
				'core/query'            => array(
					'left-featured-image' => __( 'Left Featured Image', 'blockstarter' ),
				),
				'core/post-terms'       => array(
					'term-button' => __( 'Button Style', 'blockstarter' ),
				),
				'core/query-pagination' => array(
					'pagination-button' => __( 'Button Style', 'blockstarter' ),
				),
			);
			foreach ( $block_styles as $block => $styles ) {
				foreach ( $styles as $style_name => $style_label ) {
						register_block_style(
							$block,
							array(
								'name'  => $style_name,
								'label' => $style_label,
							)
						);
				}
			}
	}

	/**
	 * Load custom block styles only when the block is used.
	 */
	public function enqueue_theme_styles() {

		if ( is_admin() ) {
			return;
		}

			// Scan our css folder to locate block styles.
			$files = glob( get_template_directory() . '/dist/css/*.css' );
		foreach ( $files as $file ) {
				// Get the filename and core block name.
				$filename   = basename( $file, '.css' );
				$block_name = str_replace( 'core-block-', 'core/', $filename );
				$version    = wp_get_theme( 'blockstarter' )->get( 'Version' );
				wp_enqueue_block_style(
					$block_name,
					array(
						'handle' => "blockstarter-block-{$filename}",
						'src'    => get_theme_file_uri( "dist/css/{$filename}.min.css" ),
						'path'   => get_theme_file_path( "dist/css/{$filename}.min.css" ),
						'ver'    => $version,
					)
				);
		}
	}

	/**
	 * Load critical CSS inline in the head.
	 */
	public function critical_css() {

		if ( is_admin() ) {
			return;
		}

		global $post;
		$css = '';

		// Core CSS files to be included.
		$files_arr = array(
			get_template_directory() . '/dist/css/core.min.css',
		);

		// Home page specific critical CSS.
		if ( is_front_page() || is_home() ) {
			$critical_css_path = get_template_directory() . '/dist/css/home.min.css';
			if ( file_exists( $critical_css_path ) ) {
				$files_arr[] = $critical_css_path;
			}
		}

		// Single post specific critical CSS.
		if ( is_single() ) {
			$critical_css_path = get_template_directory() . '/dist/css/single.min.css';
			if ( file_exists( $critical_css_path ) ) {
				$files_arr[] = $critical_css_path;
			}
		}

		// Search specific critical CSS.
		if ( is_search() ) {
			$critical_css_path = get_template_directory() . '/dist/css/search.min.css';
			if ( file_exists( $critical_css_path ) ) {
				$files_arr[] = $critical_css_path;
			}
		}

		// Archive specific critical CSS.
		if ( is_archive() ) {
			$critical_css_path = get_template_directory() . '/dist/css/archives.min.css';
			if ( file_exists( $critical_css_path ) ) {
				$files_arr[] = $critical_css_path;
			}
		}

		// Comments specific critical CSS.
		if ( is_singular() && comments_open() && get_comments_number() ) {
			$critical_css_path = get_template_directory() . '/dist/css/comments.min.css';
			if ( file_exists( $critical_css_path ) ) {
				$files_arr[] = $critical_css_path;
			}
		}

		// Read and concatenate the contents of each file.
		foreach ( $files_arr as $file_path ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$css .= file_get_contents( $file_path );
		}

		// Custom CSS for Align Full.
		if ( isset( $post->post_content ) && str_contains( $post->post_content, 'alignfull' ) ) {
			$critical_css_path = get_template_directory() . '/dist/css/alignfull.min.css';
			if ( file_exists( $critical_css_path ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				$css .= file_get_contents( $critical_css_path );
			}
		}

		// Allow filtering of the critical CSS.
		$css = apply_filters( 'critical_base_css', $css );

		if ( ! empty( $css ) ) {
			// CSS is already sanitized through file reading and filtering, safe to output directly.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo "<style id='blockstarter-critical-home-css'>{$css}</style>";
		}
	}

	/**
	 * Add template-specific styles to critical CSS.
	 *
	 * @param string $css Existing critical CSS.
	 * @return string Modified critical CSS with template styles.
	 */
	public function templates_styles( $css ) {
		$template = get_page_template_slug( get_queried_object_id() );
		if ( 'page-hero-image' === $template ) {
			$critical_css_path = get_template_directory() . '/dist/css/template-page-hero-image.min.css';
			if ( file_exists( $critical_css_path ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				$css .= file_get_contents( $critical_css_path );
			}
		}
			return $css;
	}

	/**
	 * Inject theme URL CSS variables so styles can reference theme assets via CSS variables.
	 * We register a tiny empty style handle and attach the inline CSS to it.
	 * This is needed because the main CSS is inlined as critical CSS in the head.
	 */
	public function theme_asset_css_vars() {
		$theme_uri = esc_url( get_template_directory_uri() );

		$css = ":root{\n"
			. "  --janeth-theme-url: '" . $theme_uri . "';\n"
			. "  --janeth-beauty-image: url('" . $theme_uri . "/assets/images/beauty.webp');\n"
			. "}\n";

		// Register an empty style handle so we can attach inline styles to it.
		wp_register_style( 'janethsalon-vars', false, array(), wp_get_theme()->get( 'Version' ) );
		wp_enqueue_style( 'janethsalon-vars' );
		wp_add_inline_style( 'janethsalon-vars', $css );
	}
}

new Custom_Enqueue_Styles();
