<?php
/**
 * Plugin Name: Ruth Chafin Interior Design Core Functionality
 * Plugin URI: https://github.com/Herm71/rcid-core-functionality.git
 * GitHub Plugin URI: https://github.com/Herm71/rcid-core-functionality
 * Description: Contains custom functionality. Theme independent.
 * Version: 0.0.2
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

//Include Customization files:

// General
include_once ( plugin_dir_path( __FILE__ ) . 'lib/functions/general.php' );

// Post Types
// include( plugin_dir_path( __FILE__ ) . 'lib/functions/post-types.php' );

// Taxonomies
//include( plugin_dir_path( __FILE__ ) . 'lib/functions/taxonomies.php' );

// Shortcodes
//include( plugin_dir_path( __FILE__ ) . 'lib/functions/shortcodes.php' );

// Sidebars
include_once ( plugin_dir_path( __FILE__ )  . 'lib/functions/side-bars.php' );

// Menus
include_once ( plugin_dir_path( __FILE__ ) . 'lib/functions/menus.php');

