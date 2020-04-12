<?php
/**
 * Post Types
 *
 * This file registers any custom post types
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/Herm71/blackbird-core-functionality-plugin.git
 * @author       Jason Chafin
 * @copyright    Copyright (c) 2011, Jason Chafin
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Create Rotator post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function bb_register_rotator_post_type() {
	$labels = array(
		'name' => 'Rotator Items',
		'singular_name' => 'Rotator Item',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Rotator Item',
		'edit_item' => 'Edit Rotator Item',
		'new_item' => 'New Rotator Item',
		'view_item' => 'View Rotator Item',
		'search_items' => 'Search Rotator Items',
		'not_found' =>  'No rotator items found',
		'not_found_in_trash' => 'No rotator items found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Rotator'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail','excerpt')
	);

	register_post_type( 'rotator', $args );
}
add_action( 'init', 'bb_register_rotator_post_type' );

/**
 * Create Biz Directory post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function bb_register_biz_directory_post_type() {
	$labels = array(
		'name' => 'Business Directory Items',
		'singular_name' => 'Business Directory Item',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Business Directory Item',
		'edit_item' => 'Edit Business Directory Item',
		'new_item' => 'New Business Directory Item',
		'view_item' => 'View Business Directory Item',
		'search_items' => 'Search Business Directory Items',
		'not_found' =>  'No Business Directory items found',
		'not_found_in_trash' => 'No Business Directory items found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Business Directory'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats')
	);

	register_post_type( 'business_directory', $args );
}
add_action( 'init', 'bb_register_biz_directory_post_type' );

/**
 * Create Portfolio post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function bb_register_portfolio_post_type() {
	$labels = array(
		'name' => 'Portfolio Items',
		'singular_name' => 'Portfolio Item',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Portfolio Item',
		'edit_item' => 'Edit Portfolio Item',
		'new_item' => 'New Portfolio Item',
		'view_item' => 'View Portfolio Item',
		'search_items' => 'Search Portfolio Items',
		'not_found' =>  'No Portfolio items found',
		'not_found_in_trash' => 'No Portfolio items found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Portfolio'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats')
	);

	register_post_type( 'portfolio', $args );
}
add_action( 'init', 'bb_register_portfolio_post_type' );

/**
 * Create Projects post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function bb_register_projects_post_type() {
	$labels = array(
		'name' => 'Projects',
		'singular_name' => 'Project',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Project',
		'edit_item' => 'Edit Project',
		'new_item' => 'New Project',
		'view_item' => 'View Project',
		'search_items' => 'Search Projects',
		'not_found' =>  'No Project items found',
		'not_found_in_trash' => 'No Projects found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Projects'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats')
	);

	register_post_type( 'projects', $args );
}
add_action( 'init', 'bb_register_projects_post_type' );

/**
 * Create Press post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function bb_register_press_post_type() {
	$labels = array(
		'name' => 'Press Items',
		'singular_name' => 'Press Item',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Press Item',
		'edit_item' => 'Edit Press Item',
		'new_item' => 'New Press Item',
		'view_item' => 'View Press Item',
		'search_items' => 'Search Press Items',
		'not_found' =>  'No Press Items found',
		'not_found_in_trash' => 'No Press Items found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Press'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats')
	);

	register_post_type( 'press', $args );
}
add_action( 'init', 'bb_register_press_post_type' );

/**
 * Create Team Member post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function bb_register_team_member_post_type() {
	$labels = array(
		'name' => 'Team Members',
		'singular_name' => 'Team Member',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Team Member',
		'edit_item' => 'Edit Team Member',
		'new_item' => 'New Team Member',
		'view_item' => 'View Team Member',
		'search_items' => 'Search Team Members',
		'not_found' =>  'No Team Members found',
		'not_found_in_trash' => 'No Team Members found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Team Members'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats')
	);

	register_post_type( 'team_member', $args );
}
add_action( 'init', 'bb_register_team_member_post_type' );

/**
 * Create Testimonials post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function bb_register_testimonials_post_type() {
	$labels = array(
		'name' => 'Testimonials',
		'singular_name' => 'Testimonial',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Testimonial',
		'edit_item' => 'Edit Testimonial',
		'new_item' => 'New Testimonial',
		'view_item' => 'View Testimonial',
		'search_items' => 'Search Testimonials',
		'not_found' =>  'No Testimonials found',
		'not_found_in_trash' => 'No Testimonials found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Testimonials'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats')
	);

	register_post_type( 'testimonials', $args );
}
add_action( 'init', 'bb_register_testimonials_post_type' );

