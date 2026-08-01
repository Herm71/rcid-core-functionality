<?php
/**
 * Security Headers
 *
 * This file contains the functions necessary to add security headers to the site.
 * see: https://pantheon.io/docs/wordpress-best-practices#security-headers
 *
 * @package   Core_Functionality
 * @since     1.1.0
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
 * Serialise a directive map into a Content-Security-Policy header value.
 *
 * @since 1.4.0
 *
 * @param  array $directives Map of directive name => array of source expressions.
 * @return string Header value.
 */
function rcid_build_csp( $directives ) {
	$parts = array();

	foreach ( $directives as $name => $sources ) {
		$parts[] = empty( $sources ) ? $name : $name . ' ' . implode( ' ', $sources );
	}

	return implode( '; ', $parts );
}

/**
 * The Content-Security-Policy the site currently enforces.
 *
 * NOTE: this policy is permissive to the point of being decorative. `http://*`
 * matches https origins too — CSP treats an `http:` scheme-part as matching
 * `https:` — so combined with 'unsafe-inline' and 'unsafe-eval' it effectively
 * allows everything. It is kept as-is deliberately: tightening it blind is what
 * produced the v1.1.2 and v1.1.3 hotfixes. See rcid_csp_report_only_directives()
 * for the candidate replacement being trialled, and #14.
 *
 * @since 1.4.0
 *
 * @return array Map of directive name => source expressions.
 */
function rcid_csp_enforced_directives() {
	return array(
		'default-src'     => array(
			"'self'",
			"'unsafe-inline'",
			"'unsafe-eval'",
			'http://*',
			'*.googletagmanager.com',
			'*.siteimproveanalytics.com',
			'*.pantheonsite.io',
			'*.gravatar.com',
			'*.ucsc.edu',
			'*.google-analytics.com',
			'ajax.googleapis.com',
			'use.fontawesome.com',
			'*.google.com',
			'*.netlify.com',
			'*.unpkg.com',
			'fonts.googleapis.com',
			'fonts.gstatic.com',
			'unpkg.com',
			'*.fontawesome.com',
			'blob:',
			'data:',
		),
		'worker-src'      => array( "'self'", 'blob:' ),
		'frame-src'       => array( "'self'", '*.youtube.com', '*.google.com' ),
		'object-src'      => array( "'none'" ),
		'frame-ancestors' => array( "'self'", '*.google.com', '*.youtube.com' ),
	);
}

/**
 * Candidate hardened policy, sent as report-only.
 *
 * Browsers report violations of this to the console without blocking anything,
 * so it can be evaluated against real traffic before it is enforced. Sources
 * were derived from what the live site actually loads; several origins in the
 * enforced policy (*.pantheonsite.io from the old host, *.ucsc.edu,
 * *.netlify.com, unpkg) are deliberately omitted so that any which are still
 * needed announce themselves as violations rather than being carried forward
 * on faith.
 *
 * Known soft spots, left for a follow-up: script-src still needs
 * 'unsafe-inline' and 'unsafe-eval' for the Google Tag Manager snippet, which
 * a nonce-based approach would remove.
 *
 * @since 1.4.0
 *
 * @return array Map of directive name => source expressions.
 */
function rcid_csp_report_only_directives() {
	return array(
		'default-src'     => array( "'self'" ),
		'script-src'      => array(
			"'self'",
			"'unsafe-inline'",
			"'unsafe-eval'",
			'*.googletagmanager.com',
			'*.google-analytics.com',
			'cdn.hu-manity.co',
			'ajax.googleapis.com',
		),
		'style-src'       => array(
			"'self'",
			"'unsafe-inline'",
			'fonts.googleapis.com',
			'use.fontawesome.com',
			'*.fontawesome.com',
		),
		'font-src'        => array(
			"'self'",
			'fonts.gstatic.com',
			'use.fontawesome.com',
			'*.fontawesome.com',
			'data:',
		),
		'img-src'         => array(
			"'self'",
			'data:',
			'blob:',
			'*.gravatar.com',
			'*.google-analytics.com',
			'*.googletagmanager.com',
			'*.siteimproveanalytics.com',
		),
		'connect-src'     => array(
			"'self'",
			'*.google-analytics.com',
			'*.googletagmanager.com',
			'*.siteimproveanalytics.com',
			'cdn.hu-manity.co',
		),
		// *.googletagmanager.com is required by the GTM <noscript> iframe in
		// gtm.php, which the enforced policy's frame-src does not cover today.
		'frame-src'       => array( "'self'", '*.youtube.com', '*.google.com', '*.googletagmanager.com' ),
		'worker-src'      => array( "'self'", 'blob:' ),
		'object-src'      => array( "'none'" ),
		'base-uri'        => array( "'self'" ),
		'form-action'     => array( "'self'" ),
		'frame-ancestors' => array( "'self'", '*.google.com', '*.youtube.com' ),
	);
}

/**
 * Add Security Headers
 *
 * Filters the headers WordPress sends on front-end requests. Skipped in the
 * admin so the editor is not constrained by the site's CSP.
 *
 * @since 1.1.0
 *
 * @param  array $headers Headers WordPress is about to send.
 * @return array Filtered headers.
 */
function rcid_additional_securityheaders( $headers ) {
	if ( ! is_admin() ) {
		$headers['Referrer-Policy']        = 'no-referrer-when-downgrade'; // This is the default value, the same as if it were not set.
		$headers['X-Content-Type-Options'] = 'nosniff';
		$headers['Permissions-Policy']     = 'geolocation=(self), microphone=(self), camera=(self)';
		$headers['X-Frame-Options']        = 'SAMEORIGIN';

		$headers['Content-Security-Policy']             = rcid_build_csp( rcid_csp_enforced_directives() );
		$headers['Content-Security-Policy-Report-Only'] = rcid_build_csp( rcid_csp_report_only_directives() );
	}

	return $headers;
}
add_filter( 'wp_headers', 'rcid_additional_securityheaders' );
