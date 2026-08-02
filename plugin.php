<?php
/**
 * Plugin Name: Ruth Chafin Interior Design Core Functionality
 * Plugin URI: https://github.com/Herm71/rcid-core-functionality.git
 * Description: Contains custom functionality. Theme independent.
 * Version: 1.6.0
 * Author: Jason Chafin
 * Author URI: https://github.com/Herm71
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rcid-core-functionality
 * Requires at least: 6.0
 * Requires PHP: 8.0
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package RCID_Custom_Functionality
 */

// Block direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin directory.
define( 'BB_DIR', __DIR__ );

/**
 * Plugin updates via GitHub releases.
 *
 * Only registered where an update can actually be surfaced or applied: the
 * dashboard, WP-Cron, and WP-CLI (`wp plugin update rcid-core-functionality`).
 * Front-end requests skip it.
 *
 * Deliberately not gated on wp_is_auto_update_enabled_for_type('plugin').
 * That governs unattended background auto-updates only; the update notice and
 * the Update Now button both depend on the check itself running, so gating on
 * it would hide updates from any site that has auto-updates switched off.
 *
 * @see https://github.com/YahnisElsts/plugin-update-checker
 */
$rcid_update_checker_path = BB_DIR
	. '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';

if ( ( is_admin() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) )
	&& file_exists( $rcid_update_checker_path )
) {
	include_once $rcid_update_checker_path;

	$rcid_update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/Herm71/rcid-core-functionality/',
		__FILE__,
		'rcid-core-functionality'
	);

	// Install the release asset (rcid-core-functionality.zip) and nothing else.
	// The default preference silently falls back to GitHub's source tarball,
	// which ships without vendor/ — installing that would leave the site with a
	// copy of this plugin that can no longer update itself.
	$rcid_vcs_api = $rcid_update_checker->getVcsApi();
	$rcid_vcs_api->enableReleaseAssets(
		'/rcid-core-functionality\.zip/',
		$rcid_vcs_api::REQUIRE_RELEASE_ASSETS
	);
}

// Include Customization files.

// Post Types.
if ( file_exists( BB_DIR . '/lib/functions/post-types.php' ) ) {
	include_once BB_DIR . '/lib/functions/post-types.php';
}

// Google Tag Manager.
if ( file_exists( BB_DIR . '/lib/functions/gtm.php' ) ) {
	include_once BB_DIR . '/lib/functions/gtm.php';
}

// Disable XMLRP.
if ( file_exists( BB_DIR . '/lib/functions/disable-xmlrpc.php' ) ) {
	include_once BB_DIR . '/lib/functions/disable-xmlrpc.php';
}

// Security Headers.
if ( file_exists( BB_DIR . '/lib/functions/security-headers.php' ) ) {
	include_once BB_DIR . '/lib/functions/security-headers.php';
}

// General.
if ( file_exists( BB_DIR . '/lib/functions/general.php' ) ) {
	include_once BB_DIR . '/lib/functions/general.php';
}

/**
 * Register the post types, then flush rewrite rules.
 *
 * All three post types use has_archive => true, so each needs its own rewrite
 * rules. Without a flush at activation those rules never get written and the
 * archives 404 until someone re-saves Permalinks by hand.
 *
 * Registration has to happen *before* the flush: flush_rewrite_rules() writes
 * out the rules for whatever is registered at that moment, and on activation
 * `init` has not run yet.
 *
 * Note for multisite: register_activation_hook() fires once per network
 * activation, not once per site, so sites in a network would still need their
 * permalinks re-saved. This plugin runs on a single-site install.
 *
 * @since 1.4.0
 *
 * @return void
 */
function rcid_activate() {
	// Guarded because post-types.php is included conditionally above; a missing
	// file should not turn activation into a fatal.
	if ( function_exists( 'rcid_register_post_types' ) ) {
		rcid_register_post_types();
	}

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'rcid_activate' );

/**
 * Unregister the post types, then flush rewrite rules.
 *
 * The plugin is still loaded when this runs and its post types are still
 * registered, so flushing on its own would write the archive rules straight
 * back. Unregistering first is what actually drops them.
 *
 * @since 1.4.0
 *
 * @return void
 */
function rcid_deactivate() {
	if ( function_exists( 'rcid_unregister_post_types' ) ) {
		rcid_unregister_post_types();
	}

	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'rcid_deactivate' );
