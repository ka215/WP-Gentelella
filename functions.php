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
if ( ! defined( 'WPGENT_VERSION' ) )   define( 'WPGENT_VERSION', '1.0' );
if ( ! defined( 'WPGENT_THEME_DIR' ) ) define( 'WPGENT_THEME_DIR', 'views' );
if ( ! defined( 'USE_RELATIVE_URI' ) ) define( 'USE_RELATIVE_URI', false );

add_filter( 'template_directory', function( $template_dir, $template, $theme_root ) {
  if ( 'plotter' === $template ) {
    $template_dir = WP_CONTENT_DIR . '/' . WPGENT_THEME_DIR;
  }
//var_dump( $template_dir );
  return $template_dir;
}, 10, 3 );
add_filter( 'theme_root_uri', function( $theme_root_uri, $siteurl, $stylesheet_or_template ) {
  if ( 'plotter' === $stylesheet_or_template ) {
    $theme_root_uri = WP_CONTENT_URL . '/' . WPGENT_THEME_DIR;
  }
//var_dump( $theme_root_uri );
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

/**
 * Utilities:
 * There are below functions that wrapped some common methods on classes of this plugin for using in your theme.
 */
function __ctl( $class_snippet = 'model' ) {
    if ( strpos( strtolower( $class_snippet ), 'model' ) !== false ) {
        $_instance = class_exists( 'Plotter\Models\dataModel' ) ? new Plotter\Models\dataModel : null;
    } else
    if ( strpos( strtolower( $class_snippet ), 'lib' ) !== false ) {
        $_instance = class_exists( 'Plotter\Libs\common' ) ? new Plotter\Libs\common : null;
    } else {
        $_instance = class_exists( 'Plotter\Controllers\Plotter' ) ? new Plotter\Controllers\Plotter : null;
    }
    return ! empty( $_instance ) ? $_instance : new stdClass();
}

/**
 * Initialize theme
 */
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
  
});

/**
 * Include resources for theme
 */
add_action( 'wp_enqueue_scripts', function() {
  $_pagename = __ctl( 'lib' )::get_pageinfo();
  // Stylesheets
  wp_register_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false, '3.3.7' );
  wp_enqueue_style( 'bootstrap' );
  
  wp_deregister_style( 'font-awesome' );
  wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0' );
  wp_enqueue_style( 'font-awesome' );
  
  wp_register_style( WPGENT_HANDLE . '-icon', WPGENT_DIR . 'build/css/icons.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'build/css/icons.min.css' ) ) );
  wp_enqueue_style( WPGENT_HANDLE . '-icon' );
  
  wp_register_style( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/nprogress/nprogress.css' ) ) );
  wp_enqueue_style( 'nprogress' );
  
  if ( in_array( $_pagename, array( 'login', 'register' ) ) ) {
    // Login page & Register page only
    wp_register_style( 'animate', WPGENT_DIR . 'vendors/animate.css/animate.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/animate.css/animate.min.css' ) ) );
    wp_enqueue_style( 'animate' );
    
  } else
  if ( is_front_page() ) {
    // Front page only
  } else {
    wp_register_style( 'pnotify', WPGENT_DIR . 'vendors/pnotify/dist/pnotify.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/pnotify/dist/pnotify.css' ) ) );
    wp_enqueue_style( 'pnotify' );
    
    wp_register_style( 'switchery', WPGENT_DIR . 'vendors/switchery/dist/switchery.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/switchery/dist/switchery.min.css' ) ) );
    wp_enqueue_style( 'switchery' );
    
  }
  
  // Common Custom Styles
  wp_register_style( WPGENT_HANDLE, WPGENT_DIR . 'build/css/custom.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'build/css/custom.min.css' ) ) );
  wp_enqueue_style( WPGENT_HANDLE );
  
  $font_params = array(
    get_bloginfo( 'language' ), // Current Language
    0, // is serif: 1 = true | 0 = false
    400, // base weight
  );
  wp_register_style( WPGENT_HANDLE . '-font', WPGENT_DIR . 'build/css/fonts.php?l=' . implode( ';', $font_params ), array( WPGENT_HANDLE ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'build/css/fonts.php' ) ) );
  wp_enqueue_style( WPGENT_HANDLE . '-font' );
  
  // Paged Custom Styles
  $_paged_custom_stylesheet = 'build/css/custom-'. $_pagename .'.min.js';
  if ( file_exists( WPGENT_PATH . $_paged_custom_stylesheet ) ) {
    wp_register_style( WPGENT_HANDLE .'-'. $_pagename, WPGENT_DIR . $_paged_custom_stylesheet, array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_paged_custom_stylesheet ) ) );
    wp_enqueue_style( WPGENT_HANDLE .'-'. $_pagename );
  }
  
  
  // JavaScripts
  wp_deregister_script( 'jquery' );
  wp_register_script( 'jquery', '//code.jquery.com/jquery-3.2.1.min.js', array(), '3.2.1' );
  wp_enqueue_script( 'jquery' );
  
  wp_register_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
  wp_enqueue_script( 'bootstrap' );
  
  wp_register_script( 'fastclick', WPGENT_DIR . 'vendors/fastclick/lib/fastclick.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/fastclick/lib/fastclick.js' ) ), true );
  wp_enqueue_script( 'fastclick' );
  
  wp_register_script( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/nprogress/nprogress.js' ) ), true );
  wp_enqueue_script( 'nprogress' );
  
  if ( in_array( $_pagename, array( 'login', 'register' ) ) ) {
    // Login page & Register page only
  } else
  if ( is_front_page() ) {
    // Front page only
  } else {
    wp_register_script( 'validator', WPGENT_DIR . 'vendors/validator/validator.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/validator/validator.js' ) ), true );
    wp_enqueue_script( 'validator' );
    
    wp_register_script( 'pnotify', WPGENT_DIR . 'vendors/pnotify/dist/pnotify.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/pnotify/dist/pnotify.js' ) ), true );
    wp_enqueue_script( 'pnotify' );
    
    wp_register_script( 'switchery', WPGENT_DIR . 'vendors/switchery/dist/switchery.min.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/switchery/dist/switchery.min.js' ) ), true );
    wp_enqueue_script( 'switchery' );
    
  }
  
  // Common Custom Scripts
  wp_register_script( WPGENT_HANDLE, WPGENT_DIR . 'build/js/custom.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'build/js/custom.js' ) ), true );
  wp_enqueue_script( WPGENT_HANDLE );
  
  // Paged Custom Scripts
  $_paged_custom_scriptfile = 'build/js/custom-'. $_pagename .'.js';
  if ( file_exists( WPGENT_PATH . $_paged_custom_scriptfile ) ) {
    wp_register_script( WPGENT_HANDLE .'-'. $_pagename, WPGENT_DIR . $_paged_custom_scriptfile, array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_paged_custom_scriptfile ) ), true );
    wp_enqueue_script( WPGENT_HANDLE .'-'. $_pagename );
  }
  
}, 2 );

/**
 * Finalize including resources
 */
add_action( 'wp_enqueue_scripts', function() {
  if ( is_front_page() ) {
    // Stylesheets
    $style_handles = array(
      'theme-my-login', 'nprogress', 'animate', 'pnotify', 'switchery', WPGENT_HANDLE,
      // WPGENT_HANDLE . '-icon'
    );
    foreach ( $style_handles as $_handle ) {
      wp_deregister_style( $_handle );
    }
    // JavaScripts
    $script_handles = array(
      'fastclick', 'nprogress', 'validator', 'pnotify', 'switchery', WPGENT_HANDLE,
      'tml-themed-profiles', 'wp-embed', 
    );
    foreach ( $script_handles as $_handle ) {
      wp_deregister_script( $_handle );
    }
  } else {
    // Masking resource path
    global $wp_scripts, $wp_styles;
    $masking_styles = array( 'theme-my-login' => 'theme-my-login' );
    foreach ( $wp_styles->registered as $_k => $_v ) {
      if ( array_key_exists( $_k, $masking_styles ) ) {
        $wp_styles->registered[$_k]->src = str_replace( $masking_styles[$_k], '.' . hash( 'crc32', $masking_styles[$_k] ), dirname( $_v->src ) ) . '/' . basename( $_v->src );
      }
    }
    $masking_scripts = array( 'tml-themed-profiles' => 'theme-my-login' );
    foreach ( $wp_scripts->registered as $_k => $_v ) {
      if ( array_key_exists( $_k, $masking_scripts ) ) {
        $wp_scripts->registered[$_k]->src = str_replace( $masking_scripts[$_k], '.' . hash( 'crc32', $masking_scripts[$_k] ), dirname( $_v->src ) ) . '/' . basename( $_v->src );
      }
    }
  }
}, PHP_INT_MAX );

/**
 * Inserting meta for SEO
 */
add_action( 'wp_head', function() {
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

/**
 * Inserting before enqueued styles
 */
add_action( 'wp_print_styles', function() {
  // echo '<!-- insert wp_print_styles -->';
});

/**
 * Inserting before enqueued scripts
 */
add_action( 'wp_print_scripts', function() {
  // echo '<!-- Fonts -->';
}, PHP_INT_MAX );

/**
 * Appended into head
 */
add_action( 'wp_head', function() {
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
 * 
 */
add_action( 'login_form', function() {
  //global $error;
  //$error = TRUE;
} );

/**
 * Disable login's autofocus
 *
 * @since WP 4.8.0
 */
add_filter( 'enable_login_autofocus', function(){ return false; } );

/**
 *
 */
add_filter( 'pre_option_active_plugins', function( $value ){
  return $value;
} );

/**
 * 
 */
add_action( 'wp_print_footer_scripts', function() {
  $inline_scripts = array();
  if ( is_user_logged_in() ) {
    if ( __ctl( 'lib' )::has_forms_in_page() ) {
      $notify_empty_title = __( 'Please be sure to fill here', 'wpgentelella' );
      $inline_scripts[] = <<<EOT
validator.message['empty'] = "$notify_empty_title";

$('form.withValidator')
  .on('blur', 'input[required], input.optional, select.required', validator.checkField)
  .on('change', 'select.required', validator.checkField)
  .on('keypress', 'input[required][pattern]', validator.keypress);

$('form').submit(function(e){
  e.preventDefault();
  var submit = true;
  // Validate the form using generic validaing
  if( !validator.checkAll( $(this) ) ){
    submit = false;
  }
  if( submit ) this.submit();
  return false;
});
EOT;
    }
  }
  if ( ! empty( $inline_scripts ) ) {
    echo '<script>' . PHP_EOL;
    echo implode( PHP_EOL, $inline_scripts );
    echo '</script>' . PHP_EOL;
  }
} );



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
      $page_name = __ctl( 'lib' )::get_pageinfo( 'page_name' );
      $post_guid = __ctl( 'lib' )::get_pageinfo( 'guid' );
      $page_type = __ctl( 'lib' )::get_pageinfo();
      $console_logs[] = "console.log('Current Page Name: {$page_name} (GUID: {$post_guid}) | Page Type: {$page_type}');";
    }
    if ( is_user_logged_in() ) {
      $current_user = wp_get_current_user();
      $console_logs[] = "console.log('Current User: {$current_user->display_name} ({$current_user->ID} : {$current_user->user_nicename})');";
      $console_logs[] = "console.log({ isFirstVisit: ". ( __ctl( 'lib' )::is_first_visit() ? 'true' : 'false' ) .", isDashboard: ". ( __ctl( 'lib' )::is_dashboard() ? 'true' : 'false' ) .", isProfile: ". ( __ctl( 'lib' )::is_profile() ? 'true' : 'false' ) .", hasForms: ". ( __ctl( 'lib' )::has_forms_in_page() ? 'true' : 'false' ) ."});";
    }
    $_hash = __ctl( 'lib' )::custom_hash( date("Y-m-d H:i:s") );
    $console_logs[] = "console.log('Current hash: {$_hash}');";
    if ( session_status() == PHP_SESSION_ACTIVE ) {
      $console_logs[] = "console.log( JSON.parse('". json_encode( $_SESSION ) ."') );";
    }
    
    echo '<script>' . implode( PHP_EOL, $console_logs ) . '</script>' . PHP_EOL;
  }
}, PHP_INT_MAX );
