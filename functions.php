<?php
/**
 * 
 *
 * @package WordPress
 * @subpackage WP_Gentelella
 * @since 1.0
 */

if ( ! defined( 'WPGENT_HANDLE' ) )    define( 'WPGENT_HANDLE', 'wp-gentelella' );
if ( ! defined( 'WPGENT_DOMAIN' ) )    define( 'WPGENT_DOMAIN', 'wpgentelella' );
if ( ! defined( 'WPGENT_VERSION' ) )   define( 'WPGENT_VERSION', '1.0.0' );
if ( ! defined( 'WPGENT_THEME_DIR' ) ) define( 'WPGENT_THEME_DIR', 'views' );
if ( ! defined( 'USE_RELATIVE_URI' ) ) define( 'USE_RELATIVE_URI', true );

add_filter( 'template_directory', function( $template_dir, $template, $theme_root ) {
  if ( 'plotter' === $template ) {
    $template_dir = WP_CONTENT_DIR . '/' . WPGENT_THEME_DIR;
  }
  return $template_dir;
}, 10, 3 );
add_filter( 'theme_root_uri', function( $theme_root_uri, $siteurl, $stylesheet_or_template ) {
  if ( 'plotter' === $stylesheet_or_template ) {
    $theme_root_uri = WP_CONTENT_URL . '/' . WPGENT_THEME_DIR;
  }
  return $theme_root_uri;
}, 10, 3 );
add_filter( 'template_directory_uri', function( $template_dir_uri, $template, $theme_root_uri ) {
  if ( 'plotter' === $template ) {
    $template_dir_uri = $theme_root_uri;
    if ( USE_RELATIVE_URI ) {
      $template_dir_uri = str_replace( $_SERVER['HTTP_HOST'], '', strrchr( $template_dir_uri, $_SERVER['HTTP_HOST'] ) );
    }
  }
  return $template_dir_uri;
}, 10, 3 );

if ( ! defined( 'WPGENT_PATH' ) ) define( 'WPGENT_PATH', get_template_directory() . '/' );
if ( ! defined( 'WPGENT_DIR' ) )  define( 'WPGENT_DIR', get_template_directory_uri() . '/' );


add_action( 'after_setup_theme', function() {
  
  load_theme_textdomain( WPGENT_DOMAIN );
  
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  // add_theme_support( 'automatic-feed-links' );
  
  add_image_size( WPGENT_DOMAIN . '-featured-image', 2000, 2000, true );
  add_image_size( WPGENT_DOMAIN . '-thumbnail-avatar', 140, 140, true );
  
  add_theme_support( 'custom-logo', array(
    'width'       => 250,
    'height'      => 250,
    'flex-width'  => true,
  ) );
  
  if ( is_front_page() ) {
    
    
    
  } else {
    
    $GLOBALS['content_width'] = 525;
    
    register_nav_menus( array(
      'top'    => __( 'Top Menu', WPGENT_DOMAIN ),
      'social' => __( 'Social Links Menu', WPGENT_DOMAIN ),
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
    
    add_theme_support( 'customize-selective-refresh-widgets' );
    
    //add_editor_style( array( 'assets/css/editor-style.css', twentyseventeen_fonts_url() ) );
    
    register_sidebar( array(
      'name'          => __( 'Sidebar Menu', WPGENT_DOMAIN ),
      'id'            => 'side-menu',
      'description'   => __( 'Add widgets here.', WPGENT_DOMAIN ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ) );
    
  }
  
  if ( ! is_admin() ) {
    // Cleanup in head
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles', 10 );
    add_filter( 'emoji_svg_url', '__return_false' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
    remove_action( 'wp_head', 'noindex', 1 );
    remove_action( 'wp_head', 'wp_resource_hints', 2 );

  }
  
});

/**
 * Disabled REST API other than specific namespaces
 */
add_filter( 'rest_pre_dispatch', function( $result, $wp_rest_server, $request ) {

  if ( ! is_front_page() ) {
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
  }

  return new WP_Error( 'rest_disabled', __( 'Authorization Required.', WPGENT_DOMAIN ), array( 'status' => rest_authorization_required_code() ) );
}, 10, 3 );




add_action( 'wp_enqueue_scripts', function() {
  // Stylesheets
  if ( is_front_page() ) {
    
  }
  wp_register_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false, '3.3.7' );
  wp_enqueue_style( 'bootstrap' );
  
  wp_deregister_style( 'font-awesome' );
  wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0' );
  wp_enqueue_style( 'font-awesome' );
  
  wp_register_style( WPGENT_HANDLE . '-icon', WPGENT_DIR . 'build/css/icons.min.css', false, wpgent_hash( filemtime( WPGENT_PATH . 'build/css/icons.min.css' ) ) );
  wp_enqueue_style( WPGENT_HANDLE . '-icon' );
  
  wp_register_style( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.css', false, '' );
  wp_enqueue_style( 'nprogress' );
  
  if ( in_array( get_tml_pageinfo(), array( 'login', 'register' ) ) ) {
    wp_register_style( 'animate', WPGENT_DIR . 'vendors/animate.css/animate.min.css', false, '' );
    wp_enqueue_style( 'animate' );
  }
  
  wp_register_style( WPGENT_HANDLE, WPGENT_DIR . 'build/css/custom.min.css', false, wpgent_hash( filemtime( WPGENT_PATH . 'build/css/custom.min.css' ) ) );
  wp_enqueue_style( WPGENT_HANDLE );
  
  $font_params = array(
    get_bloginfo( 'language' ), // Current Language
    0, // is serif: 1 = true | 0 = false
    400, // base weight
  );
  wp_register_style( WPGENT_HANDLE . '-font', WPGENT_DIR . 'build/css/fonts.php?l=' . implode( ';', $font_params ), array( WPGENT_HANDLE ), '' );
  wp_enqueue_style( WPGENT_HANDLE . '-font' );
  
  
  // JavaScripts
  if ( is_front_page() ) {
    
  }
  wp_deregister_script( 'jquery' );
  wp_register_script( 'jquery', '//code.jquery.com/jquery-3.2.1.min.js', array(), '3.2.1' );
  wp_enqueue_script( 'jquery' );
  
  wp_register_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
  wp_enqueue_script( 'bootstrap' );
  
  wp_register_script( 'fastclick', WPGENT_DIR . 'vendors/fastclick/lib/fastclick.js', array(), '', true );
  wp_enqueue_script( 'fastclick' );
  
  wp_register_script( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.js', array(), '', true );
  wp_enqueue_script( 'nprogress' );
  
  wp_register_script( WPGENT_HANDLE, WPGENT_DIR . 'build/js/custom.js', array(), '', true );
  wp_enqueue_script( WPGENT_HANDLE );
  
}, 2 );


add_action( 'wp_enqueue_scripts', function() {
  if ( is_front_page() ) {
    // Stylesheets
    wp_deregister_style( 'theme-my-login' );
    wp_deregister_style( 'nprogress' );
    wp_deregister_style( 'wp-gentelella' );
    //wp_deregister_style( 'wp-gentelella-font' );
    // JavaScripts
    wp_deregister_script( 'fastclick' );
    wp_deregister_script( 'nprogress' );
    wp_deregister_script( 'wp-gentelella' );
    wp_deregister_script( 'tml-themed-profiles' );
    wp_deregister_script( 'wp-embed' );
  } else {
    // Masking resource path
    global $wp_scripts, $wp_styles;
    $masking_styles = array( 'theme-my-login' => 'theme-my-login' );
    foreach ( $wp_styles->registered as $_k => $_v ) {
      if ( array_key_exists( $_k, $masking_styles ) ) {
        $wp_styles->registered[$_k]->src = str_replace( $masking_styles[$_k], hash( 'crc32', $masking_styles[$_k] ), dirname( $_v->src ) ) . '/' . basename( $_v->src );
      }
    }
    $masking_scripts = array( 'tml-themed-profiles' => 'theme-my-login' );
    foreach ( $wp_scripts->registered as $_k => $_v ) {
      if ( array_key_exists( $_k, $masking_scripts ) ) {
        $wp_scripts->registered[$_k]->src = str_replace( $masking_scripts[$_k], hash( 'crc32', $masking_scripts[$_k] ), dirname( $_v->src ) ) . '/' . basename( $_v->src );
      }
    }
  }
}, PHP_INT_MAX );

add_action( 'wp_head', function() {
  // Inserting meta for SEO
  // General Meta
  $meta_lines = array(
    'description' => '{meta description}',
    'keywords'    => '',
    'author'      => '',
  );
  foreach ( $meta_lines as $_name => $_content ) {
    if ( ! empty( $_content ) ) {
      echo '<meta name="'. $_name .'" content="'. $_content .'">' . PHP_EOL;
    }
  }
  // Open graph meta
  
}, 1 );

add_action( 'wp_print_styles', function() {
  // Inserting before enqueued styles
  // echo '<!-- insert wp_print_styles -->';
});

add_action( 'wp_print_scripts', function() {
  // Inserting before enqueued scripts
  // echo '<!-- Fonts -->';
}, PHP_INT_MAX );

add_action( 'wp_head', function() {
  // Appended into head
  echo '<!-- Site Icons -->';
  echo '<link rel="shortcut icon" href="">';
  echo '<link rel="icon" type="image/png" href="">';
  echo '<link rel="apple-touch-icon" href="">';
  echo '<link rel="apple-touch-icon" sizes="72x72" href="">';
  echo '<link rel="apple-touch-icon" sizes="114x114" href="">';
  global $is_IE;
  if ( $is_IE ) {
    echo '<!--[if lt IE 9]>';
    echo '<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>';
    echo '<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>';
    echo '<![endif]-->';
  }
}, PHP_INT_MAX );

/**
 * Clean up body classes
 */
function cleanup_classes( $classes ) {
  foreach( $classes as $_ind => $_class ) {
    if ( strpos( $_class, 'page' ) !== false ) {
      unset( $classes[$_ind] );
    }
  }
  
  return $classes;
}
add_filter( 'body_class', 'cleanup_classes' );
add_filter( 'post_class', 'cleanup_classes' );



/**
 * debug_code
 */
add_action( 'wp_footer', function() {
  if ( WP_DEBUG ) {
    global $template, $wp_query;
    $template_name = basename( $template, '.php' );
    $console_logs = [];
    $console_logs[] = "console.log('Current Page Template: {$template_name}');";
    if ( ! is_front_page() ) {
      $page_name = get_tml_pageinfo( 'page_name' );
      $post_guid = get_tml_pageinfo( 'guid' );
      $page_type = get_tml_pageinfo();
      $console_logs[] = "console.log('Current Page Name: {$page_name} (GUID: {$post_guid}) | Page Type: {$page_type}');";
    }
    if ( is_user_logged_in() ) {
      $current_user = wp_get_current_user();
      $console_logs[] = "console.log('Current User: {$current_user->display_name} ({$current_user->ID} : {$current_user->user_nicename})');";
    }
    $_hash = wpgent_hash( date("Y-m-d H:i:s") );
    $console_logs[] = "console.log('Current hash: {$_hash}');";
    
    echo '<script>' . implode( PHP_EOL, $console_logs ) . '</script>' . PHP_EOL;
  }
}, PHP_INT_MAX );


/**
 * Utilities
 */
function wpgent_hash( $str, $short=true ) {
  $os_bit = exec( 'uname -i' );
  if ( trim( $os_bit ) === 'x86_64' ) {
    $algo = boolval( $short ) ? 'sha384' : 'sha512';
  } else {
    $algo = boolval( $short ) ? 'sha1' : 'sha256';
  }
  return base64_encode( hash( $algo, $str, true ) );
}

function get_tml_pageinfo( $info_type='' ) {
  global $wp_query;
  switch ( $info_type ) {
    case 'page_name':
      $result = $wp_query->query_vars['pagename'];
      break;
    case 'guid':
      $result = ! empty( $wp_query->post ) ? $wp_query->post->guid : '';
      break;
    default:
      $result = ! empty( $wp_query->post ) ? trim( str_replace( site_url(), '', $wp_query->post->guid ), '/' ) : '';
      break;
  }
  return $result;
}
