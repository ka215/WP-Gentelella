<?php
/**
 * 
 *
 * @package WordPress
 * @subpackage WP_Gentelella
 * @since 1.0
 */
if ( ! defined( 'WPGENT_HANDLE' ) )    define( 'WPGENT_HANDLE', 'plotter' ); // 'wp-gentelella'
if ( ! defined( 'WPGENT_DOMAIN' ) )    define( 'WPGENT_DOMAIN', 'plotter' ); // WPGENT_DOMAIN
if ( ! defined( 'WPGENT_VERSION' ) )   define( 'WPGENT_VERSION', '1.4.0' );
if ( ! defined( 'WPGENT_THEME_DIR' ) ) define( 'WPGENT_THEME_DIR', 'views' );
if ( ! defined( 'USE_RELATIVE_URI' ) ) define( 'USE_RELATIVE_URI', false );

add_filter( 'template_directory', function( $template_dir, $template, $theme_root ) {
  if ( WPGENT_DOMAIN === $template ) {
    $template_dir = WP_CONTENT_DIR . '/' . WPGENT_THEME_DIR;
  }
//var_dump( $template_dir );
  return $template_dir;
}, 10, 3 );
add_filter( 'theme_root_uri', function( $theme_root_uri, $siteurl, $stylesheet_or_template ) {
  if ( WPGENT_DOMAIN === $stylesheet_or_template ) {
    $theme_root_uri = WP_CONTENT_URL . '/' . WPGENT_THEME_DIR;
  }
//var_dump( $theme_root_uri );
  return $theme_root_uri;
}, 10, 3 );
add_filter( 'template_directory_uri', function( $template_dir_uri, $template, $theme_root_uri ) {
  if ( WPGENT_DOMAIN === $template ) {
    $template_dir_uri = $theme_root_uri;
    if ( USE_RELATIVE_URI ) {
      $template_dir_uri = str_replace( $_SERVER['HTTP_HOST'], '', strrchr( $template_dir_uri, $_SERVER['HTTP_HOST'] ) );
    }
  }
  return $template_dir_uri;
}, 10, 3 );

if ( ! defined( 'WPGENT_PATH' ) )       define( 'WPGENT_PATH', get_template_directory() . '/' );
if ( ! defined( 'WPGENT_DIR' ) )        define( 'WPGENT_DIR', get_template_directory_uri() . '/' );
if ( ! defined( 'USE_CDN_RESOURCES' ) ) define( 'USE_CDN_RESOURCES', false );

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
  if ( USE_CDN_RESOURCES ) {
    wp_register_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false, '3.3.7' );
  } else {
    wp_register_style( 'bootstrap', WPGENT_DIR . 'vendors/bootstrap/dist/css/bootstrap.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/bootstrap/dist/css/bootstrap.min.css' ) ) );
  }
  wp_enqueue_style( 'bootstrap' );
  
  wp_deregister_style( 'font-awesome' );
  if ( USE_CDN_RESOURCES ) {
    wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0' );
  } else {
    wp_register_style( 'font-awesome', WPGENT_DIR . 'vendors/font-awesome/css/font-awesome.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/font-awesome/css/font-awesome.min.css' ) ) );
  }
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
  
  // Single Page Custom Styles
  $_paged_custom_stylesheet = sprintf( 'build/css/custom-%s%s.css', $_pagename, ( WP_DEBUG ? '' : '.min' ) );
  if ( file_exists( WPGENT_PATH . $_paged_custom_stylesheet ) ) {
    wp_register_style( WPGENT_HANDLE .'-'. $_pagename, WPGENT_DIR . $_paged_custom_stylesheet, array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_paged_custom_stylesheet ) ) );
    wp_enqueue_style( WPGENT_HANDLE .'-'. $_pagename );
  }
  
  
  // JavaScripts
  wp_deregister_script( 'jquery' );
  if ( USE_CDN_RESOURCES ) {
    wp_register_script( 'jquery', '//code.jquery.com/jquery-3.2.1.min.js', array(), '3.2.1' );
  } else {
    wp_register_script( 'jquery', WPGENT_DIR . 'vendors/jquery/dist/jquery.min.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/jquery/dist/jquery.min.js' ) ) );
  }
  wp_enqueue_script( 'jquery' );
  
  if ( USE_CDN_RESOURCES ) {
    wp_register_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
  } else {
    wp_register_script( 'bootstrap', WPGENT_DIR . 'vendors/bootstrap/dist/js/bootstrap.min.js', array( 'jquery' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/bootstrap/dist/js/bootstrap.min.js' ) ), true );
  }
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
  $_common_custom_scriptfile = sprintf( '%s/js/custom%s.js', ( WP_DEBUG ? 'src' : 'build' ), ( WP_DEBUG ? '' : '.min' ) );
  wp_register_script( WPGENT_HANDLE, WPGENT_DIR . $_common_custom_scriptfile, array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_common_custom_scriptfile ) ), true );
  wp_enqueue_script( WPGENT_HANDLE );
  
  // Single Page Custom Scripts
  $_paged_custom_scriptfile = sprintf( 'build/js/custom-%s%s.js', $_pagename, ( WP_DEBUG ? '' : '.min' ) );
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
      echo '<meta name="'. esc_attr( $_name ) .'" content="'. esc_attr__( $_content, WPGENT_DOMAIN ) .'">' . PHP_EOL;
    }
  }
  // Open graph meta
  $ogmeta_lines = array(
    'key' => '',
    
    
  );
  foreach ( $ogmeta_lines as $_name => $_content ) {
    if ( ! empty( $_content ) ) {
      echo '<meta name="'. esc_attr( $_name ) .'" content="'. esc_attr__( $_content, WPGENT_DOMAIN ) .'">' . PHP_EOL;
    }
  }
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
  $inline_scripts = array();
  // echo '<!-- Fonts -->';
  $inline_scripts[] = "var logger = {};";
  if ( WP_DEBUG ) {
    $inline_scripts[] = <<<EOT
// Logger for development only
logger.LEVEL = {
    RUN   : 0,
    ERROR : 1,
    WARN  : 2,
    LOG   : 3,
    INFO  : 4,
    DEBUG : 3,
    FULL  : 4
};
logger.level = logger.LEVEL.DEBUG; // Default
if ( logger.level == 1 ) logger.debug = console.error.bind( console ) 
else if ( logger.level == 2 ) logger.debug = console.warn.bind( console ) 
else if ( logger.level == 3 ) logger.debug = console.log.bind( console ) 
else if ( logger.level == 4 ) logger.debug = console.info.bind( console ) 
else logger.debug = function() {
};
EOT;
  } else {
    $inline_scripts[] = "logger.debug = function() {};";
  }
  if ( ! empty( $inline_scripts ) ) {
    echo '<script>', PHP_EOL;
    echo implode( PHP_EOL, $inline_scripts ), PHP_EOL;
    echo '</script>', PHP_EOL;
  }
}, PHP_INT_MAX );

/**
 * Appended into head
 */
add_action( 'wp_head', function() {
  $append_lines = array();
  $append_lines[] = '<!-- Site Icons -->';
  $append_lines[] = '<link rel="shortcut icon" href="">';
  $append_lines[] = '<link rel="icon" type="image/png" href="">';
  $append_lines[] = '<link rel="apple-touch-icon" href="">';
  $append_lines[] = '<link rel="apple-touch-icon" sizes="72x72" href="">';
  $append_lines[] = '<link rel="apple-touch-icon" sizes="114x114" href="">';
  global $is_IE;
  if ( $is_IE ) {
    $append_lines[] = '<!--[if lt IE 9]>';
    $append_lines[] = '<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>';
    $append_lines[] = '<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>';
    $append_lines[] = '<![endif]-->';
  }
  if ( ! empty( $append_lines ) ) {
    echo implode( PHP_EOL, $append_lines ), PHP_EOL;
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
 * /
add_action( 'template_redirect', function(){
  $_plotter = get_query_var( 'plotter', [] );
} );
*/


/**
 * 
 */
add_action( 'wp_print_footer_scripts', function() {
  $inline_scripts = array();
  if ( is_user_logged_in() ) {
    if ( __ctl( 'lib' )::has_forms_in_page() ) {
      $notify_empty_title = __( 'Please be sure to fill here', WPGENT_DOMAIN );
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
    global $template; // , $wp_query;
    $_plotter = get_query_var( 'plotter' );
    $template_name = basename( $template, '.php' );
    $log_style = [ "'color: red; font-weight: bold'", "'color: blue; font-weight: bold'" ];
    $debug_logs = [];
    //$debug_logs[] = "logger.level = logger.LEVEL.FULL;";
    $debug_logs[] = "logger.debug('Current Page Template: %c{$template_name}', {$log_style[0]} );";
    if ( ! is_front_page() ) {
      $page_name = __ctl( 'lib' )::get_pageinfo( 'page_name' );
      $post_guid = __ctl( 'lib' )::get_pageinfo( 'guid' );
      $page_type = __ctl( 'lib' )::get_pageinfo();
      $debug_logs[] = "logger.debug('Current Page Name: %c{$page_name}%c (GUID: %c{$post_guid}%c) | Page Type: %c{$page_type}', {$log_style[0]}, '', {$log_style[0]}, '', {$log_style[0]});";
    }
    if ( file_exists( WPGENT_PATH . 'build/css/custom-'. $_plotter['page_name'] .'.css' ) ) {
      $debug_logs[] = "logger.debug('Current Page Custom Style: %ccustom-{$_plotter['page_name']}.css', {$log_style[0]} );";
    }
    if ( file_exists( WPGENT_PATH . 'build/js/custom-'. $_plotter['page_name'] .'.js' ) ) {
      $debug_logs[] = "logger.debug('Current Page Custom Script: %ccustom-{$_plotter['page_name']}.js', {$log_style[0]} );";
    }
    if ( is_user_logged_in() ) {
      $current_user = wp_get_current_user();
      $debug_logs[] = "logger.debug('Current User: %c{$current_user->display_name}%c (%c{$current_user->ID}%c : %c{$current_user->user_nicename}%c)', {$log_style[0]}, '', {$log_style[0]}, '', {$log_style[0]}, '');";
      $debug_logs[] = "logger.debug({ isFirstVisit: ". ( __ctl( 'lib' )::is_first_visit() ? 'true' : 'false' ) .", isDashboard: ". ( __ctl( 'lib' )::is_dashboard() ? 'true' : 'false' ) .", isProfile: ". ( __ctl( 'lib' )::is_profile() ? 'true' : 'false' ) .", hasForms: ". ( __ctl( 'lib' )::has_forms_in_page() ? 'true' : 'false' ) ."});";
    }
    $_hash = __ctl( 'lib' )::custom_hash( date("Y-m-d H:i:s") );
    $debug_logs[] = "logger.debug('Current hash: %c{$_hash}', {$log_style[1]});";
    if ( session_status() == PHP_SESSION_ACTIVE ) {
      $debug_logs[] = "logger.debug( JSON.parse('". json_encode( $_SESSION ) ."') );";
    }
    
    echo '<script>' . implode( PHP_EOL, $debug_logs ) . '</script>' . PHP_EOL;
  }
}, PHP_INT_MAX );
