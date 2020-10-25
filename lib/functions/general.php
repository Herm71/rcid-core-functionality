<?php
/**
 * General
 *
 * This file contains any general functions
 *
 * @package      BB_Custom_Functionality
 * @since        0.0.2
 * @link         https://github.com/Herm71/blackbird-core-functionality-plugin.git
 * @author       Jason Chafin
 * @copyright    Copyright (c) 2015, Blackbird Consulting
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

 /**
  * Callback function for Text Domain
  *
  * @param [type] $r
  * @param [type] $url
  * @return void
  * Description
  * @package
  * @since 0.0.2
  * @author Jason Chafin
  * @link http://www.blackbirdconsult.com
  * @license GNU General Public License 2.0+
  */
/**
 * Don't Update Plugin
 * @since 0.0.2
 *
 * This prevents you being prompted to update if there's a public plugin
 * with the same name.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */
function bb_custom_functionality_hidden( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}
add_filter( 'http_request_args', 'bb_custom_functionality_hidden', 5, 2 );



// Use shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// $obj = get_post_type_object('portfolio');

// echo '<pre>';
// print_r($obj);
// echo '</pre>';

 //Top Menu Callback
 function rcid_top_menu (){
    if (has_nav_menu('top-menu')) {
        wp_nav_menu( array(
            'theme_location' => 'top-menu',
            'container' =>  'div',
            'container_class' => 'top-navigation menu-secondary-menu-container',
            'fallback_cb' => '',
         ));
    }
}
 /**
 * Top bar sidebar.
 */
add_action( 'widgets_init', 'rcid_topbar_widget' );
function rcid_topbar_widget() {
    register_sidebar( array(
        'name'          => __( 'Topbar', 'ruth-chafin-interior-design' ),
        'id'            => 'top-sidebar',
        'class'         => 'top-sidebar',
        'description'   => __( 'Widgets in this area will be shown in the Top bar.', 'ruth-chafin-interior-design' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ) );
}
