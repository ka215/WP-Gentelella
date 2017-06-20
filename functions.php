<?php
/**
 * 
 *
 * @package WordPress
 * @subpackage WP_Gentelella
 * @since 1.0
 */

if ( ! defined( 'WPGENT_VERSION' ) ) define( 'WPGENT_VERSION', '0.0.1' );
if ( ! defined( 'WPGENT_PATH' ) )    define( 'WPGENT_PATH', get_template_directory() . '/' );
if ( ! defined( 'WPGENT_DIR' ) )     define( 'WPGENT_DIR', get_template_directory_uri() . '/' );


function wpgentelella_setup() {
  
  load_theme_textdomain( 'wpgentelella' );
  
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'automatic-feed-links' );
  
  add_image_size( 'wpgentelella-featured-image', 2000, 2000, true );
  add_image_size( 'wpgentelella-thumbnail-avatar', 140, 140, true );
  
  $GLOBALS['content_width'] = 525;
  
  register_nav_menus( array(
    'top'    => __( 'Top Menu', 'wpgentelella' ),
    'social' => __( 'Social Links Menu', 'wpgentelella' ),
  ) );
  
  add_theme_support( 'html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
  ) );
  
  add_theme_support( 'post-formats', array(
    'aside',
    'image',
    'video',
    'quote',
    'link',
    'gallery',
    'audio',
  ) );
  
  add_theme_support( 'custom-logo', array(
    'width'       => 250,
    'height'      => 250,
    'flex-width'  => true,
  ) );
  
  add_theme_support( 'customize-selective-refresh-widgets' );
  
  //add_editor_style( array( 'assets/css/editor-style.css', twentyseventeen_fonts_url() ) );
  
  register_sidebar( array(
    'name'          => __( 'Sidebar Menu', 'wpgentelella' ),
    'id'            => 'side-menu',
    'description'   => __( 'Add widgets here.', 'wpgentelella' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ) );
  
  
  if ( ! is_admin() ) {
    // Cleanup in head
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles', 10 );
    add_filter( 'emoji_svg_url', '__return_false' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
  }
  
}
add_action( 'after_setup_theme', 'wpgentelella_setup' );

/**
 * Disabled REST API other than specific namespaces
 */
function nendebcom_deny_restapi_except_embed( $result, $wp_rest_server, $request ){

  $namespaces = $request->get_route();

  $white_list = array(
    'oembed/', // /oembed/1.0
    'jetpack/',// /jetpack/v4
    'contact-form-7/', // /contact-form-7/v1
  );
  foreach ( $white_list as $allowed_path ) {
    if( strpos( $namespaces, $allowed_path ) === 1 ) {
      return $result;
    }
  }

  return new WP_Error( 'rest_disabled', __( 'Authorization Required.', 'wpgentelella' ), array( 'status' => rest_authorization_required_code() ) );
}
add_filter( 'rest_pre_dispatch', 'nendebcom_deny_restapi_except_embed', 10, 3 );



function wpgentelella_scripts() {
  // Stylesheets
  wp_register_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false, '3.3.7' );
  wp_enqueue_style( 'bootstrap' );
  
  wp_deregister_style( 'font-awesome' );
  wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0' );
  wp_enqueue_style( 'font-awesome' );
  
  wp_register_style( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.css', false, '' );
  wp_enqueue_style( 'nprogress' );
  
  $ver_hash = wpgent_hash( filemtime( WPGENT_PATH . 'build/css/custom.min.css' ) );
  wp_register_style( 'wp-gentelella', WPGENT_DIR . 'build/css/custom.min.css', false, $ver_hash );
  wp_enqueue_style( 'wp-gentelella' );
  
  $font_params = array(
    get_bloginfo( 'language' ), // Current Language
    0, // is serif: 1 = true | 0 = false
    400, // base weight
  );
  wp_register_style( 'wp-gentelella-font', WPGENT_DIR . 'build/fonts/font-face.php?l=' . implode( ';', $font_params ), array( 'wp-gentelella' ), '' );
  wp_enqueue_style( 'wp-gentelella-font' );
  
  
  // JavaScripts
  wp_deregister_script( 'jquery' );
  wp_register_script( 'jquery', '//code.jquery.com/jquery-3.2.1.min.js', array(), '3.2.1' );
  wp_enqueue_script( 'jquery' );
  
  wp_register_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
  wp_enqueue_script( 'bootstrap' );
  
  wp_register_script( 'fastclick', WPGENT_DIR . 'vendors/fastclick/lib/fastclick.js', array(), '', true );
  wp_enqueue_script( 'fastclick' );
  
  wp_register_script( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.js', array(), '', true );
  wp_enqueue_script( 'nprogress' );
  
  wp_register_script( 'wp-gentelella', WPGENT_DIR . 'build/js/custom.js', array(), '', true );
  wp_enqueue_script( 'wp-gentelella' );
  
}
add_action( 'wp_enqueue_scripts', 'wpgentelella_scripts', 2 );






function debug_code() {
  if ( WP_DEBUG ) {
    global $template;
    $template_name = basename( $template, '.php' );
    $console_logs = [];
    $console_logs[] = "console.log('Current Page Template: {$template_name}');";
    if ( is_user_logged_in() ) {
      $current_user = wp_get_current_user();
      $console_logs[] = "console.log('Current User: {$current_user->display_name} ({$current_user->ID} : {$current_user->user_nicename})');";
    }
    $_hash = wpgent_hash( date("Y-m-d H:i:s") );
    $console_logs[] = "console.log('Current hash: {$_hash}');";
    echo '<script>' . implode( PHP_EOL, $console_logs ) . '</script>' . PHP_EOL;
  }
}
add_action( 'wp_footer', 'debug_code', PHP_INT_MAX );

function wpgent_hash( $str, $short=true ) {
  $os_bit = exec( 'uname -i' );
  if ( trim( $os_bit ) === 'x86_64' ) {
    $algo = boolval( $short ) ? 'sha384' : 'sha512';
  } else {
    $algo = boolval( $short ) ? 'sha1' : 'sha256';
  }
  return base64_encode( hash( $algo, $str, true ) );
}
