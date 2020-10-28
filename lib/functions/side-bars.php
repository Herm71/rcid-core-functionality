<?php
 /**
 * Top bar sidebar.
 */
add_action( 'widgets_init', 'rcid_topbar_sidebar' );
function rcid_topbar_sidebar() {
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

/**
 * Footer sidebars.
 */
add_action( 'widgets_init', 'rcid_footer_one_widget' );
function rcid_footer_one_widget() {
    register_sidebar( array(
        'name'          => __( 'Footer One', 'ruth-chafin-interior-design' ),
        'id'            => 'footer-one',
        'class'         => 'footer-one',
        'description'   => __( 'Widgets in this area will be shown in footer.', 'ruth-chafin-interior-design' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ) );
}
add_action( 'widgets_init', 'rcid_footer_two_widget' );
function rcid_footer_two_widget() {
    register_sidebar( array(
        'name'          => __( 'Footer Two', 'ruth-chafin-interior-design' ),
        'id'            => 'footer-two',
        'class'         => 'footer-two',
        'description'   => __( 'Widgets in this area will be shown in footer.', 'ruth-chafin-interior-design' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ) );
}
add_action( 'widgets_init', 'rcid_footer_three_widget' );
function rcid_footer_three_widget() {
    register_sidebar( array(
        'name'          => __( 'Footer Three', 'ruth-chafin-interior-design' ),
        'id'            => 'footer-three',
        'class'         => 'footer-three',
        'description'   => __( 'Widgets in this area will be shown in footer.', 'ruth-chafin-interior-design' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ) );
}
