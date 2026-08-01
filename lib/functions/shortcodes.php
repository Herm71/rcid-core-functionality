<?php
/**
 * Custom Shortcodes
 *
 * This file contains any custom shortcodes
 *
 * @package   Core_Functionality
 * @since     1.0.0
 * @link      https://github.com/Herm71/rcid-core-functionality.git
 * @author    Jason Chafin
 * @copyright Copyright (c) 2011, Jason Chafin
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Block direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show the current year.
 *
 * Uses wp_date() rather than date() so the year rolls over according to the
 * site's configured timezone rather than the server's.
 *
 * @since 1.0.0
 *
 * @return string The current four-digit year.
 */
function rcid_show_current_year() {
	return wp_date( 'Y' );
}

add_shortcode( 'show-current-year', 'rcid_show_current_year' );
