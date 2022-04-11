<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );


// add custom header

function themename_custom_header_setup() {
    $args = array(
        'default-image'      => get_template_directory_uri() . 'img/default-image.jpg',
        'default-text-color' => '000',
        'width'              => 1200,
        'height'             => 400,
        'flex-width'         => false,
        'flex-height'        => false,
        'video'              => true,
    );
        
    add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'themename_custom_header_setup' );

function custom_video_header_locations( $active ) {
  if( is_home()  ) {
    return true;
  }

  return false;
}

add_filter( 'header_video_settings', 'header_video_resolution');

function header_video_resolution( $settings ) {
  $settings['minWidth'] = 420;
  $settings['minHeight'] = 100;
  return $settings;
}

add_filter( 'the_title', 'remove_page_title', 10, 2 );
function remove_page_title( $title, $id ) {

    if(is_front_page() && !is_null( $id )){
        
        return '';
    }else{
        return $title;
         }
}

//removes title withput removing menu 
function wpse309151_remove_title_filter_nav_menu( $nav_menu, $args ) {
    // we are working with menu, so remove the title filter
    remove_filter( 'the_title', 'remove_page_title', 10, 2 );
    return $nav_menu;

}

// this filter fires just before the nav menu item creation process
add_filter( 'pre_wp_nav_menu', 'wpse309151_remove_title_filter_nav_menu', 10, 2 );

function wpse309151_add_title_filter_non_menu( $items, $args ) {
    // we are done working with menu, so add the title filter back
    add_filter( 'the_title', 'remove_page_title', 10, 2 );
    return $items;

}

// this filter fires after nav menu item creation is done
add_filter( 'wp_nav_menu_items', 'wpse309151_add_title_filter_non_menu', 10, 2 );

//add theme support for wide and full blocks
add_theme_support( 'align-wide' );


//case study cpt
// Our custom post type function
function create_posttype() {
 
    register_post_type( 'case_study',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Case Studies' ),
                'singular_name' => __( 'Case Study' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'case-study'),
            'show_in_rest' => true,
            'menu_icon'    => 'dashicons-clipboard',
            'supports' => array('title', 'editor', 'thumbnail','comments'),
            'capability_type' => 'post',
 
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

// remove author cat and archive from archive header
add_filter( 'get_the_archive_title', function ($title) {    
        if ( is_category() ) {    
                $title = single_cat_title( '', false );    
            } elseif ( is_tag() ) {    
                $title = single_tag_title( '', false );    
            } elseif ( is_author() ) {    
                $title = '<span class="vcard">' . get_the_author() . '</span>' ;    
            } elseif ( is_tax() ) { //for custom post types
                $title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
            } elseif (is_post_type_archive()) {
                $title = post_type_archive_title( '', false );
            }
        return $title;    
});

// shorten excerpt
add_filter( 'excerpt_length', function($length) {
    return 20;
}, PHP_INT_MAX );

//custom image block glutenburg
