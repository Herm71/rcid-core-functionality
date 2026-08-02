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
		// *.googletagmanager.com is required by the GTM <noscript> iframe in
		// gtm.php. frame-src is the one directive here that actually restricts
		// anything — it is set explicitly, so it does not fall back to the
		// permissive default-src above, and without this source the iframe is
		// blocked. Temporary: the hardened policy below already covers it, and
		// this whole function goes away at the flip.
		'frame-src'       => array( "'self'", '*.youtube.com', '*.google.com', '*.googletagmanager.com' ),
		'object-src'      => array( "'none'" ),
		'frame-ancestors' => array( "'self'", '*.google.com', '*.youtube.com' ),
	);
}

/**
 * Where browsers should send violations of the report-only policy.
 *
 * Read from the RCID_CSP_REPORT_URI constant rather than hard-coded, because
 * collector URLs embed an account identifier that should not sit in a public
 * repository — anyone holding it can flood the account with junk reports. Set
 * it in wp-config.php:
 *
 *     define( 'RCID_CSP_REPORT_URI', 'https://xxxxx.report-uri.com/r/d/csp/reportOnly' );
 *
 * Returns an empty string when unset, in which case no reporting directives
 * and no Reporting-Endpoints header are emitted at all. Failing closed matters
 * here: a malformed or placeholder URL would make every page load fire a
 * pointless cross-origin POST.
 *
 * @since 1.6.0
 *
 * @return string Validated https URL, or '' when reporting is not configured.
 */
function rcid_csp_report_uri() {
	if ( ! defined( 'RCID_CSP_REPORT_URI' ) || ! is_string( RCID_CSP_REPORT_URI ) ) {
		return '';
	}

	$url = esc_url_raw( trim( RCID_CSP_REPORT_URI ), array( 'https' ) );

	return is_string( $url ) ? $url : '';
}

/**
 * Where the modern Reporting API should deliver the same violations.
 *
 * Some collectors — report-uri.com among them — hand out a different URL for
 * the Reporting API than for the legacy report-uri directive. Set
 * RCID_CSP_REPORT_TO_URI in wp-config.php when that is the case; otherwise the
 * report-uri endpoint is reused, which is correct for most collectors.
 *
 * @since 1.6.0
 *
 * @return string Validated https URL, or '' when reporting is not configured.
 */
function rcid_csp_report_to_uri() {
	if ( defined( 'RCID_CSP_REPORT_TO_URI' ) && is_string( RCID_CSP_REPORT_TO_URI ) ) {
		$url = esc_url_raw( trim( RCID_CSP_REPORT_TO_URI ), array( 'https' ) );

		if ( is_string( $url ) && '' !== $url ) {
			return $url;
		}
	}

	return rcid_csp_report_uri();
}

/**
 * Candidate hardened policy, sent as report-only.
 *
 * Browsers report violations of this without blocking anything, so it can be
 * evaluated against real traffic before it is enforced. Sources were derived
 * from what the live site actually loads; several origins in the enforced
 * policy (*.pantheonsite.io from the old host, *.ucsc.edu, *.netlify.com,
 * unpkg) are deliberately omitted so that any which are still needed announce
 * themselves as violations rather than being carried forward on faith.
 *
 * Those announcements only reach anyone if RCID_CSP_REPORT_URI is set — see
 * rcid_csp_report_uri(). Unset, violations land in each individual visitor's
 * devtools console and are lost, which makes "no violations were reported"
 * indistinguishable from "nobody was looking".
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
	$directives = array(
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

	$report_uri = rcid_csp_report_uri();

	if ( '' !== $report_uri ) {
		// Both directives, deliberately. report-uri is deprecated but is still
		// what Safari and older browsers honour; report-to is what Chromium
		// uses and ignores report-uri when present. Sending only one loses a
		// whole class of visitor from the sample this policy is meant to build.
		$directives['report-uri'] = array( $report_uri );
		$directives['report-to']  = array( 'csp-endpoint' );
	}

	return $directives;
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

		// Names the group the report-only policy's report-to directive refers
		// to. Without this header that directive resolves to nothing and
		// Chromium sends no reports, silently.
		$report_to_uri = rcid_csp_report_to_uri();

		if ( '' !== $report_to_uri ) {
			$headers['Reporting-Endpoints'] = 'csp-endpoint="' . $report_to_uri . '"';
		}
	}

	return $headers;
}
add_filter( 'wp_headers', 'rcid_additional_securityheaders' );
