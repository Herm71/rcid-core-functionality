<?php
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
