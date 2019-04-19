<?php
/**
 * @package     WordPress
 * @author      "Your Name <name@example.com>"
 * @copyright   Organization
 * @license     GPL-2.0+ (if you want open-source)
 *
 * @wordpress-plugin
 * Plugin Name: my-site-name Custom Plugin
 * Plugin URI:  https://example.com
 * Description: Custom post-types, custom taxonomies, and more
 * Version:     0.1.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * Text Domain: mynamehjere-custom-plugin
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * 
 * #################################
 * ########### Changelog ###########
 * #################################
 * 
 * -0.1.0: Adding custom post types and taxonomies
 */

/**
 * ##################################
 * ########### Taxonomies ###########
 * ##################################
 * 
 * Each taxonomy follows a similar pattern:
 *  1- Setup add_action hook to attach custom taxonomy function to the 'init' sequence of Wordpress
 *  2- Establish function wrapper that calls when init fires in WP, under 32 characters
 *  3- Custom function houses WP core function register_taxonomy
 *  4- register_taxonomy has three arguments:
 *   -Slug name of taxonomy (string)
 *   -One or more post-types taxonomy will attach to in wordpress (array)
 *   -Taxonomy visibility/setup parameters (array)
 *  5- All taxonomies are setup as hierarchical because we want to lock the lists so other users dont cause harm 
 */

/* Example custom taxonomy */
function custom_tax() {
  register_taxonomy( 'custom-taxonomy', 'custom-post-type', array(
    'labels' => array(
      'add_new_item' => 'Add New Custom Taxonomy',
      'all_items' => 'All Custom Taxonomies',
      'edit_item' => 'Edit Custom Taxonomy',
      'menu_name' => 'Custom Taxonomiess',
      'name' => 'Custom Taxonomies',
      'new_item' => 'New Custom Taxonomy',
      'not_found' => 'No Custom Taxonomies Found',
      'not_found_in_trash' => 'No Custom Taxonomies Found in Trash',
      'parent' => 'Parent of Custom Taxonomy',
      'search_items' => 'Search Custom Taxonomies',
      'singular_name' => 'Custom Taxonomy',
      'view_item' => 'View Custom Taxonomy'
    ),
    'hierarchical' => true,
    'public' => true,
		'rewrite' => array(
      'hierarchical' => true,
      'slug' => 'my-slug-here',
      'with_front' => true
		),
    'query_var' => true,
    'show_in_rest' => true,
    'rest_base' => 'my-base-here',
    'rest_controller_class' => 'WP_REST_Terms_Controller',
    'show_ui' => true
  ));
}
add_action( 'init', 'custom_tax' );

/** 
 * ##################################
 * ########### Post Types ###########
 * ##################################
 * 
 * Custom Post Type pattern:
 *  1- Setup add_action hook to attach custom post type function to the 'init' sequence of Wordpress
 *  2- Establish function wrapper that calls when init fires in WP, under 32 characters
 *  3- Custom function houses WP core function register_post_type
 *  4- register_post_type has two arguments:
 *    -Slug name of taxonomy (string)
 *    -Post type visibility/setup parameters (array)
 */

/* Story Example */
function aa_story_cpt() {
	register_post_type( 'story', array(
  	'labels' => array(
			'add_new_item' => 'Add New  Story',
      'all_items' => 'All  Stories',
      'edit_item' => 'Edit  Story',
      'name' => ' Stories',
      'new_item' => 'New  Story',
      'not_found' => 'No  Stories Found',
      'not_found_in_trash' => 'No  Stories Found in Trash',
      'parent' => 'Parent of  Story',
      'search_items' => 'Search  Stories',
      'singular_name' => ' Story',
      'view_item' => 'View  Story'
    ),
    'description' => 'This is the post-type for the magazine\'s long-form articles.',
    'exclude_from_search' => false,
    'has_archive' => true,
		'public' => true,
    'publicly_queryable' => true,
    'show_in_nav_menus' => true,
    'show_in_rest' => true,
    'rest_base' => 'story',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'show_ui' => true,
    'supports' => array( 'title', 'custom-fields', 'editor' ),
    'rewrite' => array(
      'slug' => 'story',
      'with_front' => false
    )
  ));
}
add_action( 'init', 'aa_story_cpt' );


/** 
 * ##############################
 * ########### Images ###########
 * ##############################
 * 
 * This will add additional sizes to the media upload sizes available.
 * See Settings > Media Settings for Thumbnail | Medium | Large Image sizes
 * Also note if you use the 'Full' size in the theme, that is whatever size was uploaded
 * 
 * Setting image widths to match up with the new media queries
 * 'extra-large'    = 1600px
 * 'large'          = 1200px
 * 'medium-large'   = 800px
 * 'medium'         = 400px 
 * 'thumbnail'      = 400px x 400px square
 */

function custom_image_sizes() {
  add_image_size('extra-large', 1600, 9999 );
  // 'large' is set at 1200px width in /wp-admin/options-media.php
  add_image_size('medium-large', 800, 9999); 
  // 'medium' is set at 400px width in /wp-admin/options-media.php
  // 'thumbnail' is set at 400px x 400px in /wp-admin/options-media.php
}
add_action( 'init', 'custom_image_sizes' );


/*
 * ###############################
 * ########### Filters ###########
 * ###############################
 * 
 * Filters interupt bad content outputs on site and replace them with things people want to see.
 */

/* OPTIONAL: Get rid of wp-embed JS for performance improvement */
function remove_wpembed() {
	if(!is_admin()) {
		remove_action('rest_api_init', 'wp_oembed_register_route'); // Remove the REST API endpoint.
	  remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10); // Turn off oEmbed auto discovery. Don't filter oEmbed results.
	  remove_action('wp_head', 'wp_oembed_add_discovery_links'); // Remove oEmbed discovery links.
		wp_deregister_script( 'wp-embed' ); // Gets rid of wp-embed script
	}
}
add_action('init', 'remove_wpembed');

/* OPTIONAL: Get rid of wp-emoji JS for performance improvement */
function remove_wpemoji() {
	if(!is_admin()) {
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_scripts', 'print_emoji_detection_script' );
		remove_action('admin_print_styles', 'print_emoji_styles' );
	}
}
add_action('init', 'remove_wpemoji');

/* OPTIONAL: Get rid of jQuery migrate to improve performance */
function dequeue_jquery_migrate( &$scripts){
	if(!is_admin()){
		$scripts->remove( 'jquery');
		$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
	}
}
add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );

/** 
 * OPTIONAL: Hides templates you dont want to see
 */ 
function redirect_templates() {
  if( is_singular( 'post-type-slug-here' ) || is_post_type_archive( 'post-type-slug-here' ) ) {
    wp_redirect( home_url(), 301 );
    exit;
  }
}
add_action('template_redirect', 'redirect_templates');

/**
 * #############################
 * ########### Menus ###########
 * #############################
 *
 * OPTIONAL: 
 * This code changes the 'Post' label to 'Whatever' across the site
 * Renames Categories taxonomy into Something
 *
 * 1- Tell add_action hook to invoke change_post_label function
 * 2- change_post_label function changes admin menu names
 * 3- Add changed array of labels in change_post_object function
 * 4- change_post_object function changes label language to 'Whatever'
 */

add_action( 'admin_menu', 'change_post_label' );
add_action( 'init', 'change_post_object' );

// Both of the functions rename post > whatever
function change_post_label() {
  global $menu;
  global $submenu;
  $menu[5][0] = 'Whatever';
  $submenu['edit.php'][5][0] = 'Whatever';
  $submenu['edit.php'][10][0] = 'Add Whatever';
  $submenu['edit.php'][16][0] = 'Whatever Tags';
  echo '';
}

function change_post_object() {
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->add_new = 'Add Whatever';
  $labels->add_new_item = 'Add Whatever';
  $labels->all_items = 'All Whatever';
  $labels->edit_item = 'Edit Whatever';
  $labels->menu_name = 'Whatever';
  $labels->name = 'Whatever';
  $labels->name_admin_bar = 'Whatever';
  $labels->new_item = 'Whatever';
  $labels->not_found = 'No Whatever found';
  $labels->not_found_in_trash = 'No Whatever found in Trash';
  $labels->search_items = 'Search Whatever';
  $labels->singular_name = 'Whatever';
  $labels->view_item = 'View Whatever';
}

// Renames Categories taxonomy into Something
function rename_category() {
  global $wp_taxonomies;
  $cat = $wp_taxonomies['category'];
  $cat->labels->singular_name = 'Something';
  $cat->labels->add_new = 'Add Something';
  $cat->labels->add_new_item = 'Add Something';
  $cat->labels->all_items = 'All Somethings';
  $cat->labels->edit_item = 'Edit Something';
  $cat->labels->menu_name = 'Somethings';
  $cat->labels->name = 'Somethings';
  $cat->labels->name_admin_bar = 'Somethings';
  $cat->labels->new_item = 'Something';
  $cat->labels->not_found = 'No Somethings found';
  $cat->labels->not_found_in_trash = 'No Somethings found in Trash';
  $cat->labels->search_items = 'Search Somethings';
  $cat->labels->singular_name = 'Something';
  $cat->labels->view_item = 'View Something';
}
add_action('init', 'rename_category');

?>
