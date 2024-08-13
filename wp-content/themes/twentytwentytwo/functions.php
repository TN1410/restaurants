<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */


if ( ! function_exists( 'twentytwentytwo_support' ) ) :
	function twentytwentytwo_support() {
		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );
		// Enqueue editor styles.
		add_editor_style( 'style.css' );
	}
endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);
		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );

        wp_enqueue_script('jquery-3-6-0-min-js', get_template_directory_uri() . '/assets/js/jquery-3.6.0.min.js', false);


	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );




// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';


// Add theme support for post thumbnails
add_theme_support('post-thumbnails');

function create_restaurants_cpt() {
    $labels = array(
        'name' => _x('Restaurants', 'Post Type General Name', 'textdomain'),
        'singular_name' => _x('Restaurant', 'Post Type Singular Name', 'textdomain'),
        'menu_name' => __('Restaurants', 'textdomain'),
        'name_admin_bar' => __('Restaurant', 'textdomain'),
        'archives' => __('Restaurant Archives', 'textdomain'),
        'attributes' => __('Restaurant Attributes', 'textdomain'),
        'parent_item_colon' => __('Parent Restaurant:', 'textdomain'),
        'all_items' => __('All Restaurants', 'textdomain'),
        'add_new_item' => __('Add New Restaurant', 'textdomain'),
        'add_new' => __('Add New', 'textdomain'),
        'new_item' => __('New Restaurant', 'textdomain'),
        'edit_item' => __('Edit Restaurant', 'textdomain'),
        'update_item' => __('Update Restaurant', 'textdomain'),
        'view_item' => __('View Restaurant', 'textdomain'),
        'view_items' => __('View Restaurants', 'textdomain'),
        'search_items' => __('Search Restaurant', 'textdomain'),
        'not_found' => __('Not found', 'textdomain'),
        'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
        'featured_image' => __('Featured Image', 'textdomain'),
        'set_featured_image' => __('Set featured image', 'textdomain'),
        'remove_featured_image' => __('Remove featured image', 'textdomain'),
        'use_featured_image' => __('Use as featured image', 'textdomain'),
        'insert_into_item' => __('Insert into restaurant', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this restaurant', 'textdomain'),
        'items_list' => __('Restaurants list', 'textdomain'),
        'items_list_navigation' => __('Restaurants list navigation', 'textdomain'),
        'filter_items_list' => __('Filter restaurants list', 'textdomain'),
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'restaurants' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'         => array( 'category', 'post_tag' ), // Include categories and tags
    );
    register_post_type('restaurants', $args);
}
add_action('init', 'create_restaurants_cpt', 0);


// Ckeditor show
add_filter('use_block_editor_for_post', '__return_false');





function get_qr_code_url($post_id) {
    $post_url = get_permalink($post_id);
    $qr_code_url = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=" . urlencode($post_url);
    return $qr_code_url;
}
function restaurants_add_meta_boxes() {
    add_meta_box(
        'restaurants_textarea', // ID of the meta box
        'Textarea', // Title of the meta box
        'restaurants_textarea_callback', // Callback function
        'restaurants', // Post type
        'normal', // Context
        'high' // Priority
    );
    add_meta_box(
        'restaurants_price',
        'Price',
        'restaurants_price_callback',
        'restaurants',
        'normal',
        'high'
    );
    add_meta_box(
        'restaurants_link',
        'link',
        'restaurants_link_callback',
        'restaurants',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'restaurants_add_meta_boxes');
function restaurants_textarea_callback($post) {
    // Add a nonce field so we can check for it later.
    wp_nonce_field('restaurants_save_meta_box_data', 'restaurants_meta_box_nonce');
    $value = get_post_meta($post->ID, '_restaurants_textarea', true);
    echo '<label for="restaurants_textarea">Textarea</label>';
    echo '<textarea id="restaurants_textarea" name="restaurants_textarea" rows="5" cols="50" style="width:100%;">' . esc_textarea($value) . '</textarea>';
}
function restaurants_price_callback($post) {
    wp_nonce_field('restaurants_save_meta_box_data', 'restaurants_meta_box_nonce');
    $value = get_post_meta($post->ID, '_restaurants_price', true);
    echo '<label for="restaurants_price">Price</label>';
    echo '<input type="text" id="restaurants_price" name="restaurants_price" value="' . esc_attr($value) . '" style="width:100%;" />';
}
function restaurants_link_callback($post) {
    wp_nonce_field('restaurants_save_meta_box_data', 'restaurants_meta_box_nonce');
    $value = get_post_meta($post->ID, '_restaurants_link', true);
    echo '<label for="restaurants_link">Link</label>';
    echo '<input type="text" id="restaurants_link" name="restaurants_link" value="' . esc_attr($value) . '" style="width:100%;" />';
}
function save_meta_box($post_id) {
    // Check if our nonce is set.
    if (!isset($_POST['restaurants_meta_box_nonce'])) {
        return $post_id;
    }
    $nonce = $_POST['restaurants_meta_box_nonce'];
    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'restaurants_save_meta_box_data')) {
        return $post_id;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    $fields = [
        'restaurants_textarea',
        'restaurants_price',
        'restaurants_link'
    ];
    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_meta_box');
