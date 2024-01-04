<?php
/**
 * Plugin Name: Ruth Chafin Interior Design Core Functionality
 * Plugin URI: https://github.com/Herm71/rcid-core-functionality.git
 * GitHub Plugin URI: https://github.com/Herm71/rcid-core-functionality
 * Description: Contains custom functionality. Theme independent.
 * Version: 1.1.0
 * Author: Jason Chafin
 * Author URI: https://github.com/Herm71
 * License: GPL2
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// Plugin Directory
define( 'BB_DIR', dirname( __FILE__ ) );

//Include Customization files:

// Post Types
include_once( BB_DIR . '/lib/functions/post-types.php' );

// Google Tag Manager
include_once( BB_DIR . '/lib/functions/gtm.php' );

// Shortcodes
// include_once( BB_DIR . '/lib/functions/shortcodes.php' );

// Disable XMLRP
include_once( BB_DIR . '/lib/functions/disable-xmlrpc.php' );

// Security Headers
include_once( BB_DIR . '/lib/functions/security-headers.php' );

// General
include_once( BB_DIR . '/lib/functions/general.php' );
