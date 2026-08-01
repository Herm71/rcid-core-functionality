<?php
/**
 * Post Types
 *
 * This file registers any custom post types
 *
 * @package   Core_Functionality
 * @since     1.0.0
 * @link      https://github.com/Herm71/rcid-core-functionality.git
 * @author    Jason Chafin
 * @copyright Copyright (c) 2011, Jason Chafin
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Block direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create Projects post type
 *
 * @since 1.0.0
 * @link  http://codex.wordpress.org/Function_Reference/register_post_type
 */
function rcid_register_projects_post_type() {
	$labels = array(
		'name'               => 'Projects',
		'singular_name'      => 'Project',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Project',
		'edit_item'          => 'Edit Project',
		'new_item'           => 'New Project',
		'view_item'          => 'View Project',
		'search_items'       => 'Search Projects',
		'not_found'          => 'No Project items found',
		'not_found_in_trash' => 'No Projects found in trash',
		'parent_item_colon'  => '',
		'menu_name'          => 'Projects',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true, // To use Gutenberg editor.
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats' ),
	);

	register_post_type( 'projects', $args );
}

/**
 * Create Press post type
 *
 * @since 1.0.0
 * @link  http://codex.wordpress.org/Function_Reference/register_post_type
 */
function rcid_register_press_post_type() {
	$labels = array(
		'name'               => 'Press Items',
		'singular_name'      => 'Press Item',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Press Item',
		'edit_item'          => 'Edit Press Item',
		'new_item'           => 'New Press Item',
		'view_item'          => 'View Press Item',
		'search_items'       => 'Search Press Items',
		'not_found'          => 'No Press Items found',
		'not_found_in_trash' => 'No Press Items found in trash',
		'parent_item_colon'  => '',
		'menu_name'          => 'Press',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true, // To use Gutenberg editor.
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats' ),
	);

	register_post_type( 'press', $args );
}

/**
 * Create Team Member post type
 *
 * @since 1.0.0
 * @link  http://codex.wordpress.org/Function_Reference/register_post_type
 */
function rcid_register_team_member_post_type() {
	$labels = array(
		'name'               => 'Team Members',
		'singular_name'      => 'Team Member',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Team Member',
		'edit_item'          => 'Edit Team Member',
		'new_item'           => 'New Team Member',
		'view_item'          => 'View Team Member',
		'search_items'       => 'Search Team Members',
		'not_found'          => 'No Team Members found',
		'not_found_in_trash' => 'No Team Members found in trash',
		'parent_item_colon'  => '',
		'menu_name'          => 'Team Members',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true, // To use Gutenberg editor.
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats' ),
	);

	register_post_type( 'team_member', $args );
}
// The team_member post type is defined but deliberately not registered; leave
// this commented out unless enabling it is explicitly asked for.
// phpcs:ignore Squiz.PHP.CommentedOutCode.Found,Squiz.Commenting.InlineComment.InvalidEndChar -- Intentionally disabled registration.
// add_action( 'init', 'rcid_register_team_member_post_type' );

/**
 * Create Testimonials post type
 *
 * @since 1.0.0
 * @link  http://codex.wordpress.org/Function_Reference/register_post_type
 */
function rcid_register_testimonials_post_type() {
	$labels = array(
		'name'               => 'Testimonials',
		'singular_name'      => 'Testimonial',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Testimonial',
		'edit_item'          => 'Edit Testimonial',
		'new_item'           => 'New Testimonial',
		'view_item'          => 'View Testimonial',
		'search_items'       => 'Search Testimonials',
		'not_found'          => 'No Testimonials found',
		'not_found_in_trash' => 'No Testimonials found in trash',
		'parent_item_colon'  => '',
		'menu_name'          => 'Testimonials',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true, // To use Gutenberg editor.
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'post-formats' ),
	);

	register_post_type( 'testimonials', $args );
}

/**
 * Slugs of every post type this plugin registers.
 *
 * Single source of truth so the registration, unregistration and activation
 * paths cannot drift apart. team_member is deliberately absent: it is defined
 * above but never registered.
 *
 * @since 1.4.0
 *
 * @return string[] Post type slugs.
 */
function rcid_post_type_slugs() {
	return array( 'projects', 'press', 'testimonials' );
}

/**
 * Register every post type this plugin provides.
 *
 * Hooked to init, and called directly by the activation hook in plugin.php.
 * Both paths must register the same post types: the activation hook flushes
 * rewrite rules, and rules generated before a post type is registered will not
 * contain its archive.
 *
 * @since 1.4.0
 *
 * @return void
 */
function rcid_register_post_types() {
	rcid_register_projects_post_type();
	rcid_register_press_post_type();
	rcid_register_testimonials_post_type();
}
add_action( 'init', 'rcid_register_post_types' );

/**
 * Unregister every post type this plugin provides.
 *
 * Called on deactivation before flushing, so the archive rewrite rules are
 * dropped rather than regenerated — at deactivation the plugin is still loaded
 * and its post types are still registered, so flushing alone would write them
 * straight back.
 *
 * @since 1.4.0
 *
 * @return void
 */
function rcid_unregister_post_types() {
	foreach ( rcid_post_type_slugs() as $post_type ) {
		if ( post_type_exists( $post_type ) ) {
			unregister_post_type( $post_type );
		}
	}
}
