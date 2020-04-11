<?

/**

 * Custom Shortcodes

 *

 * This file contains any custom shortcodes 

 *

 * @package      Core_Functionality

 * @since        1.0.0

 * @link         https://github.com/Herm71/blackbird-core-functionality-plugin.git

 * @author       Jason Chafin <Jason@blackbirdconsult.com>

 * @copyright    Copyright (c) 2012, Jason Chafin

 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License

 */

 

/**
 * Test function
 */

function say_hello() {

echo 'Hello';

}

add_shortcode('say-hello', 'say_hello');

/**
 * Show Current Year
 *
 * @return void
 * Description
 * @package
 * @since
 * @author Jason Chafin
 * @link http://www.blackbirdconsult.com
 * @license GNU General Public License 2.0+
 */

function show_current_year(){

	return date('Y');

}

add_shortcode('show_current_year', 'show_current_year');