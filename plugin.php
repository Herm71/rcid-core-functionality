<?php
/**
 * Plugin Name: Ruth Chafin Interior Design Core Functionality
 * Plugin URI: https://github.com/Herm71/rcid-core-functionality.git
 * Description: Contains custom functionality. Theme independent.
 * Version: 1.2.2
 * Author: Jason Chafin
 * Author URI: https://github.com/Herm71
 * License: GPL2
 * Text Domain: rcid-core-functionality
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Block direct access.
if (! defined('ABSPATH') ) {
    exit;
}

// Plugin Directory
define('BB_DIR', dirname(__FILE__));

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

if (( is_admin() || wp_doing_cron() || ( defined('WP_CLI') && WP_CLI ) )
    && file_exists($rcid_update_checker_path)
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
if (file_exists(BB_DIR . '/lib/functions/post-types.php') ) {
    include_once BB_DIR . '/lib/functions/post-types.php';
}

// Google Tag Manager.
if (file_exists(BB_DIR . '/lib/functions/gtm.php') ) {
    include_once BB_DIR . '/lib/functions/gtm.php';
}

// Shortcodes.
// if ( file_exists( BB_DIR . '/lib/functions/shortcodes.php' ) ) {
//     include_once BB_DIR . '/lib/functions/shortcodes.php';
// }

// Disable XMLRP.
if (file_exists(BB_DIR . '/lib/functions/disable-xmlrpc.php') ) {
    include_once BB_DIR . '/lib/functions/disable-xmlrpc.php';
}

// Security Headers.
if (file_exists(BB_DIR . '/lib/functions/security-headers.php') ) {
    include_once BB_DIR . '/lib/functions/security-headers.php';
}

// General.
if (file_exists(BB_DIR . '/lib/functions/general.php') ) {
    include_once BB_DIR . '/lib/functions/general.php';
}
