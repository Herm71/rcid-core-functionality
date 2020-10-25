<?php
/**
 * Register custom menus
 * @package storefront-child
 */


function rcid_custom_menus() {
    register_nav_menus(
      array(
        'top-menu' => __( 'Top Menu' , 'ruth-chafin-interior-design'),
      )
    );
  }
  add_action( 'init', 'rcid_custom_menus' );


?>

