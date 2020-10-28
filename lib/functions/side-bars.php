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
        'before_widget' => '<div class="rcid-header-bar-widget site-search">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widget-title topbar-widget-title">',
        'after_title'   => '</span>'
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
        'before_widget' => '<div id="footer-one" class="footer-one-widget widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widget-title footer-widget-title footer-one-widget-title">',
        'after_title'   => '</span>'
    ) );
}
add_action( 'widgets_init', 'rcid_footer_two_widget' );
function rcid_footer_two_widget() {
    register_sidebar( array(
        'name'          => __( 'Footer Two', 'ruth-chafin-interior-design' ),
        'id'            => 'footer-two',
        'class'         => 'footer-two',
        'description'   => __( 'Widgets in this area will be shown in footer.', 'ruth-chafin-interior-design' ),
        'before_widget' => '<div id="footer-two" class="footer-two-widget widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widget-title footer-widget-title footer-two-widget-title">',
        'after_title'   => '</span>'
    ) );
}
add_action( 'widgets_init', 'rcid_footer_three_widget' );
function rcid_footer_three_widget() {
    register_sidebar( array(
        'name'          => __( 'Footer Three', 'ruth-chafin-interior-design' ),
        'id'            => 'footer-three',
        'class'         => 'footer-three',
        'description'   => __( 'Widgets in this area will be shown in footer.', 'ruth-chafin-interior-design' ),
        'before_widget' => '<div id="footer-one" class="footer-three-widget widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widget-title footer-widget-title footer-one-widget-title">',
        'after_title'   => '</span>'
    ) );
}