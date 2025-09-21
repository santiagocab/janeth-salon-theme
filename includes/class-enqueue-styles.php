<?php

class Blockstarter_Enqueue_Styles {

	public function __construct() {
		add_action( 'init', array( $this, 'blockstarter_enqueue_custom_block_styles' ) );
		add_action( 'wp_head', array( $this, 'blockstarter_critical_css_for_homepage' ), 999 );
	}

	// Load custom block styles only when the block is used.
	public function blockstarter_enqueue_custom_block_styles() {
		$files = glob( get_template_directory() . '/dist/css/*.css' );
		foreach ( $files as $file ) {
			$filename = basename( $file, '.css' );
			$block_name = str_replace( 'core-block-', 'core/', $filename );
			$version = wp_get_theme( 'blockstarter' )->get( 'Version' );
			wp_enqueue_block_style( $block_name, array(
				'handle' => "blockstarter-block-{$filename}",
				'src'    => get_theme_file_uri( "dist/css/{$filename}.min.css" ),
				'path'   => get_theme_file_path( "dist/css/{$filename}.min.css" ),
				'ver'   => $version,
			) );
		}
	}

	// Load critical CSS inline in the head.
	public function blockstarter_critical_css_for_homepage() {

		$css = '';
		$files_arr = [];

		// Core CSS files to be included
		$critical_css_path = get_template_directory() . '/dist/css/core.min.css';
		if ( file_exists( $critical_css_path ) ) {
			$files_arr[] = $critical_css_path;
		}

		// Home page specific critical CSS
		if ( is_front_page() || is_home() ) {
			$critical_css_path = get_template_directory() . '/dist/css/home.min.css';
			if ( file_exists( $critical_css_path ) ) {
				$files_arr[] = $critical_css_path;
			}
		}

		// Read and concatenate the contents of each file
		foreach ( $files_arr as $file_path ) {
			$css .= file_get_contents( $file_path );
		}	

		if ( ! empty( $css ) ) {
			echo "<style id='blockstarter-critical-home-css'>{$css}</style>";
		}

	}

}
new Blockstarter_Enqueue_Styles();