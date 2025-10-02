<?php
/**
 * Theme Name: Janeth Salon Theme Functions File
 *
 * PHP version 8.2
 *
 * Functions and definitions for Janeth Salon theme.
 * This file contains the main functions and includes for the Janeth Salon WordPress theme.
 * It loads various theme components including custom post types, block patterns, styles,
 * and optimization classes.
 *
 * @category Theme
 * @package  JanethSalon
 * @author   Santiago Cabrera <santiagocab1@gmail.com>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @since    1.0.0
 */

require_once get_template_directory() . '/includes/setup-theme.php';
require_once get_template_directory() . '/includes/class-custom-enqueue-scripts.php';
require_once get_template_directory() . '/includes/class-custom-enqueue-styles.php';
require_once get_template_directory() . '/includes/optimization-core-cover-block.php';
require_once get_template_directory() . '/includes/class-optimizations-image.php';
require_once get_template_directory() . '/includes/class-custom-block-styles.php';
require_once get_template_directory() . '/includes/class-custom-block-patterns.php';
require_once get_template_directory() . '/includes/class-optimization-plugin-cf7.php';
require_once get_template_directory() . '/includes/class-custom-archives-functionality.php';
require_once get_template_directory() . '/includes/class-custom-single-post-functionality.php';
