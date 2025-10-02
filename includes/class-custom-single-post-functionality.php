<?php
/**
 * Single Post Functionality
 *
 * @package JanethSalon
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Single Post Functionality class.
 *
 * Handles custom functionality for single post pages including date formatting and comment form modifications.
 */
class Custom_Single_Post_Functionality {

	/**
	 * Constructor.
	 *
	 * Initializes the class and sets up the necessary hooks.
	 */
	public function __construct() {
		add_filter( 'render_block_core/post-date', array( $this, 'post_date_linebreaks_regex' ), 10, 1 );
		add_filter( 'comment_form_default_fields', array( $this, 'my_theme_custom_comment_fields_with_placeholders' ), 10, 1 );
		add_filter( 'comment_form_defaults', array( $this, 'my_theme_custom_comment_form_args' ), 10, 1 );
	}

	/**
	 * Replace spaces with <br/> for the rendered core/post-date block when viewing single post.
	 *
	 * @param string $block_content Block HTML output.
	 * @return string Modified HTML.
	 */
	public function post_date_linebreaks_regex( $block_content ) {

		if ( is_single() || is_search() ) {
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

	/**
	 * Modifies the default comment form fields to include placeholders derived from their labels.
	 *
	 * @param array $fields The default comment form fields.
	 * @return array Modified comment form fields.
	 */
	public function my_theme_custom_comment_fields_with_placeholders( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );
		$html_req  = ( $req ? " required='required'" : '' );

		// Get the original label for the name field and create the new field.
		$name_label       = wp_strip_all_tags( $fields['author'] );
		$fields['author'] = '<p class="comment-form-author">
				<!-- <label for="author">' . __( 'Name', 'textdomain' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> -->
				<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="Your ' . esc_attr( $name_label ) . '"' . $aria_req . $html_req . ' />
		</p>';

		// Get the original label for the email field and create the new field.
		$email_label     = wp_strip_all_tags( $fields['email'] );
		$fields['email'] = '<p class="comment-form-email">
				<!-- <label for="email">' . __( 'Email', 'textdomain' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> -->
				<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" placeholder="Your ' . esc_attr( $email_label ) . '"' . $aria_req . $html_req . ' />
		</p>';

		// Get the original label for the URL field and create the new field.
		$url_label     = wp_strip_all_tags( $fields['url'] );
		$fields['url'] = '<p class="comment-form-url">
				<!-- <label for="url">' . __( 'Website', 'textdomain' ) . '</label> -->
				<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="' . esc_attr( $url_label ) . '" />
		</p>';

		return $fields;
	}

	/**
	 * Modifies the comment form defaults, using the comment label text as the textarea placeholder.
	 *
	 * This function handles the textarea specifically, as it is separate from the default fields.
	 *
	 * @param array $args The default comment form arguments.
	 * @return array Modified comment form arguments.
	 */
	public function my_theme_custom_comment_form_args( $args ) {
		// Get the comment label's text without any HTML tags.
		$comment_label_text = wp_strip_all_tags( $args['comment_field'] );

		// Build the new comment field markup with the custom placeholder.
		$args['comment_field'] = '<p class="comment-form-comment">
				<!-- <label for="comment">' . _x( 'Comment', 'noun' ) . ' <span class="required">*</span></label> -->
				<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="Your Comment *"></textarea>
		</p>';

		return $args;
	}
}

new Custom_Single_Post_Functionality();
