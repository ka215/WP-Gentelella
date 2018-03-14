<?php
/**
 * Functions of theme for Plotter
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
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
if ( ! defined( 'SIDEBAR_SEARCH' ) )    define( 'SIDEBAR_SEARCH', false );

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

function __localize_messages() {
    // JavaScript用の翻訳テキスト定義:
    return [
      'loading' => __( 'Please Wait...', 'plotter' ), // custom.js: showLoading()
      'dialog_yes' => __( 'Ok', 'plotter' ), // custom.js: dialog()
      'dialog_no' => __( 'Cancel', 'plotter' ), // custom.js: dialog()
      'switch_src_ttl' => __( 'Switch Manageable Story', 'plotter' ), // custom.js: Top Navigation
      'switch_src_msg' => __( 'Any unsaved data will be lost. Are you sure?', 'plotter' ), // custom.js: Top Navigation
      'act_num' => __( 'Act %d', 'plotter' ), // custom-create-new.js: 
      
    ];
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
  
  add_theme_support( 'custom-logo', [
    'width'       => 250,
    'height'      => 250,
    'flex-width'  => true,
  ] );
  
  if ( is_front_page() ) {
    
    
    
  } else {
    
    $GLOBALS['content_width'] = 525;
    
    register_nav_menus( [
      'top'    => __( 'Top Menu', WPGENT_DOMAIN ),
      'social' => __( 'Social Links Menu', WPGENT_DOMAIN ),
    ] );
    
    add_theme_support( 'html5', [
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ] );
    
    add_theme_support( 'post-formats', [
      'aside',
      'image',
      'video',
      'quote',
      'link',
      'gallery',
      'audio',
    ] );
    
    add_theme_support( 'customize-selective-refresh-widgets' );
    
    //add_editor_style( array( 'assets/css/editor-style.css', twentyseventeen_fonts_url() ) );
    
    register_sidebar( [
      'name'          => __( 'Sidebar Menu', WPGENT_DOMAIN ),
      'id'            => 'side-menu',
      'description'   => __( 'Add widgets here.', WPGENT_DOMAIN ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ] );
    
  }
  
});

/**
 * Register resources for plotter
 */
add_action( 'wp_enqueue_scripts', function() {
  // $_pagename = __ctl( 'lib' )::get_pageinfo();
  $_plotter  = get_query_var( 'plotter' );
  $_pagename = $_plotter['page_name'];
  // Stylesheets
  // bootstrap:
  if ( USE_CDN_RESOURCES ) {
    wp_register_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false, '3.3.7' );
  } else {
    wp_register_style( 'bootstrap', WPGENT_DIR . 'vendors/bootstrap/dist/css/bootstrap.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/bootstrap/dist/css/bootstrap.min.css' ) ) );
  }
  
  // font-awesome
  wp_deregister_style( 'font-awesome' );
  if ( USE_CDN_RESOURCES ) {
    wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0' );
  } else {
    wp_register_style( 'font-awesome', WPGENT_DIR . 'vendors/font-awesome/css/font-awesome.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/font-awesome/css/font-awesome.min.css' ) ) );
  }
  
  // icons:
  wp_register_style( WPGENT_HANDLE . '-icon', WPGENT_DIR . 'build/css/icons.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'build/css/icons.min.css' ) ) );
  
  // noprogress:
  wp_register_style( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/nprogress/nprogress.css' ) ) );
  
  // animate:
  wp_register_style( 'animate', WPGENT_DIR . 'vendors/animate.css/animate.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/animate.css/animate.min.css' ) ) );
  
  // PNotify (BrightTheme): no used
  wp_register_style( 'pnotify', WPGENT_DIR . 'node_modules/pnotify/dist/PNotifyBrightTheme.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/PNotifyBrightTheme.css' ) ) );
  
  // vendoers styles:
  $vendor_styles = [
    // 'pnotify'     => 'vendors/pnotify/dist/pnotify.css',
    'switchery'   => 'vendors/switchery/dist/switchery.min.css',
    'smartwizard' => 'vendors/jQuery-Smart-Wizard/styles/smart_wizard.css',
    // etc.
  ];
  foreach ( $vendor_styles as $_handle => $_path ) {
    wp_register_style( $_handle, WPGENT_DIR . $_path, false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_path ) ) );
  }
  
  // Common Custom Styles
  wp_register_style( WPGENT_HANDLE, WPGENT_DIR . 'build/css/custom.min.css', false, __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'build/css/custom.min.css' ) ) );
  
  $font_params = [
    get_bloginfo( 'language' ), // Current Language
    0, // is serif: 1 = true | 0 = false
    400, // base weight
  ];
  wp_register_style( WPGENT_HANDLE . '-font', WPGENT_DIR . 'build/css/fonts.php?l=' . implode( ';', $font_params ), array( WPGENT_HANDLE ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'build/css/fonts.php' ) ) );
  
  // Single Page Custom Styles
  $_paged_custom_stylesheet = sprintf( 'build/css/custom-%s%s.css', $_pagename, ( WP_DEBUG ? '' : '.min' ) );
  if ( file_exists( WPGENT_PATH . $_paged_custom_stylesheet ) ) {
    wp_register_style( WPGENT_HANDLE .'-'. $_pagename, WPGENT_DIR . $_paged_custom_stylesheet, [], __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_paged_custom_stylesheet ) ) );
  }
  
  
  // JavaScripts
  // jquery:
  wp_deregister_script( 'jquery' );
  if ( USE_CDN_RESOURCES ) {
    wp_register_script( 'jquery', '//code.jquery.com/jquery-3.2.1.min.js', array(), '3.2.1' );
  } else {
    wp_register_script( 'jquery', WPGENT_DIR . 'vendors/jquery/dist/jquery.min.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/jquery/dist/jquery.min.js' ) ) );
  }
  
  // bootstrap:
  if ( USE_CDN_RESOURCES ) {
    wp_register_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
  } else {
    wp_register_script( 'bootstrap', WPGENT_DIR . 'vendors/bootstrap/dist/js/bootstrap.min.js', array( 'jquery' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/bootstrap/dist/js/bootstrap.min.js' ) ), true );
  }
  
  // fastclick:
  wp_register_script( 'fastclick', WPGENT_DIR . 'vendors/fastclick/lib/fastclick.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/fastclick/lib/fastclick.js' ) ), true );
  
  // nprogress:
  wp_register_script( 'nprogress', WPGENT_DIR . 'vendors/nprogress/nprogress.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'vendors/nprogress/nprogress.js' ) ), true );
  
  // PNotify:
  wp_register_script( 'pnotify', WPGENT_DIR . 'node_modules/pnotify/dist/iife/PNotify.js', array(), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/iife/PNotify.js' ) ), true );
  wp_register_script( 'pnotify-animate', WPGENT_DIR . 'node_modules/pnotify/dist/iife/PNotifyAnimate.js', array( 'pnotify' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/iife/PNotifyAnimate.js' ) ), true );
  wp_register_script( 'pnotify-buttons', WPGENT_DIR . 'node_modules/pnotify/dist/iife/PNotifyButtons.js', array( 'pnotify' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/iife/PNotifyButtons.js' ) ), true );
  wp_register_script( 'pnotify-confirm', WPGENT_DIR . 'node_modules/pnotify/dist/iife/PNotifyConfirm.js', array( 'pnotify' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/iife/PNotifyConfirm.js' ) ), true );
  wp_register_script( 'pnotify-desktop', WPGENT_DIR . 'node_modules/pnotify/dist/iife/PNotifyDesktop.js', array( 'pnotify' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/iife/PNotifyDesktop.js' ) ), true );
  wp_register_script( 'pnotify-history', WPGENT_DIR . 'node_modules/pnotify/dist/iife/PNotifyHistory.js', array( 'pnotify' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/iife/PNotifyHistory.js' ) ), true );
  wp_register_script( 'pnotify-mobile', WPGENT_DIR . 'node_modules/pnotify/dist/iife/PNotifyMobile.js', array( 'pnotify' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . 'node_modules/pnotify/dist/iife/PNotifyMobile.js' ) ), true );
  
  // vendoers scripts:
  $vendor_scripts = [
    'validator'   => 'vendors/validator/validator.js',
    // 'pnotify'     => 'vendors/pnotify/dist/pnotify.js',
    'switchery'   => 'vendors/switchery/dist/switchery.min.js',
    'smartwizard' => 'vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js',
    // etc.
  ];
  foreach ( $vendor_scripts as $_handle => $_path ) {
    wp_register_script( $_handle, WPGENT_DIR . $_path, [], __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_path ) ), true );
  }
  
  // Common Custom Scripts
  $_common_custom_scriptfile = sprintf( '%s/js/custom%s.js', ( WP_DEBUG ? 'src' : 'build' ), ( WP_DEBUG ? '' : '.min' ) );
  wp_register_script( WPGENT_HANDLE, WPGENT_DIR . $_common_custom_scriptfile, array( 'wp-api' ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_common_custom_scriptfile ) ), true );
  
  wp_localize_script( WPGENT_HANDLE, 'localize_messages', __localize_messages() );
  
  // Single Page Custom Scripts
  $_paged_custom_scriptfile = sprintf( 'build/js/custom-%s%s.js', $_pagename, ( WP_DEBUG ? '' : '.min' ) );
  if ( file_exists( WPGENT_PATH . $_paged_custom_scriptfile ) ) {
    wp_register_script( WPGENT_HANDLE .'-'. $_pagename, WPGENT_DIR . $_paged_custom_scriptfile, array( WPGENT_HANDLE ), __ctl( 'lib' )::custom_hash( filemtime( WPGENT_PATH . $_paged_custom_scriptfile ) ), true );
  }
  
}, 2 );

/**
 * Finalize enqueue resources
 */
add_action( 'wp_enqueue_scripts', function() {
  $_plotter  = get_query_var( 'plotter' );
  $_pagename = $_plotter['page_name'];
  $registered_style_handles = [
    'common' => [
      'bootstrap', 'font-awesome', WPGENT_HANDLE . '-icon', 
      'animate', 
    ],
    'wp' => [
      'theme-my-login',
    ],
    'vendoers' => [
      'nprogress', 
      'switchery', 'smartwizard',
    ],
    'extend' => [
      WPGENT_HANDLE, WPGENT_HANDLE . '-font', WPGENT_HANDLE .'-'. $_pagename,
    ],
  ];
  $registered_script_handles = [
    'common' => [
      'jquery', 'bootstrap', 
    ],
    'wp' => [
      'tml-themed-profiles', 'wp-embed', 
    ],
    'pnotify' => [
      'pnotify', 'pnotify-animate', 'pnotify-buttons', 'pnotify-confirm', 'pnotify-desktop', 'pnotify-history', 'pnotify-mobile', 
    ],
    'vendoers' => [
      'fastclick', 'nprogress', 'validator', 
      'switchery', 'smartwizard', 
    ],
    'extend' => [
      WPGENT_HANDLE, WPGENT_HANDLE .'-'. $_pagename,
    ],
  ];
  if ( is_front_page() ) {
    // Stylesheets
    $enqueue_style_handles = array_merge( $registered_style_handles['common'], $registered_style_handles['extend'] );
    foreach ( $enqueue_style_handles as $_handle ) {
      wp_enqueue_style( $_handle );
    }
    wp_deregister_style( 'theme-my-login' );
    // JavaScripts
    $enqueue_script_handles = array_merge( $registered_script_handles['common'], $registered_script_handles['extend'] );
    foreach ( $enqueue_script_handles as $_handle ) {
      wp_enqueue_script( $_handle );
    }
    wp_deregister_script( 'tml-themed-profiles' );
  } else {
    // Masking resource path
    global $wp_scripts, $wp_styles;
    $masking_styles = [
      'theme-my-login' => 'theme-my-login'
    ];
    foreach ( $wp_styles->registered as $_k => $_v ) {
      if ( array_key_exists( $_k, $masking_styles ) ) {
        $wp_styles->registered[$_k]->src = str_replace( $masking_styles[$_k], '.' . hash( 'crc32', $masking_styles[$_k] ), dirname( $_v->src ) ) . '/' . basename( $_v->src );
      }
    }
    $masking_scripts = [
      'tml-themed-profiles' => 'theme-my-login'
    ];
    foreach ( $wp_scripts->registered as $_k => $_v ) {
      if ( array_key_exists( $_k, $masking_scripts ) ) {
        $wp_scripts->registered[$_k]->src = str_replace( $masking_scripts[$_k], '.' . hash( 'crc32', $masking_scripts[$_k] ), dirname( $_v->src ) ) . '/' . basename( $_v->src );
      }
    }
    
    $enqueue_style_handles = array_merge( $registered_style_handles['common'], $registered_style_handles['wp'] );
    $enqueue_script_handles = array_merge( $registered_script_handles['common'], $registered_script_handles['wp'], $registered_script_handles['pnotify'] );
    switch ( $_pagename ) {
      case 'account':
        $enqueue_style_handles = array_merge( $enqueue_style_handles, [ 'nprogress', 'switchery' ] );
        $enqueue_script_handles = array_merge( $enqueue_script_handles, [ 'fastclick', 'nprogress', 'validator', 'switchery' ] );
        break;
      case 'dashboard':
      case 'whole-story':
        $enqueue_style_handles = array_merge( $enqueue_style_handles, [ 'nprogress', 'switchery' ] );
        $enqueue_script_handles = array_merge( $enqueue_script_handles, [ 'fastclick', 'nprogress', 'validator', 'switchery' ] );
        break;
      case 'create-new':
      case 'edit-storyline':
        $enqueue_style_handles = array_merge( $enqueue_style_handles, [ 'nprogress', ] ); // 'switchery', 'smartwizard' 
        $enqueue_script_handles = array_merge( $enqueue_script_handles, [ 'fastclick', 'nprogress', 'validator', ] ); // 'switchery', 'smartwizard' 
        break;
      default:
        
    }
    $enqueue_style_handles = array_merge( $enqueue_style_handles, $registered_style_handles['extend'] );
    foreach ( $enqueue_style_handles as $_handle ) {
      wp_enqueue_style( $_handle );
    }
    $enqueue_script_handles = array_merge( $enqueue_script_handles, $registered_script_handles['extend'] );
    foreach ( $enqueue_script_handles as $_handle ) {
      wp_enqueue_script( $_handle );
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
 * Render bootstrap modal container
 */
add_action( 'wp_footer', function() {
  
  
}, PHP_INT_MAX - 1 );


/**
 * Override the template path of Theme My Login
 */
add_filter( 'tml_template_paths', function( $args ) {
  $args[0] = WPGENT_PATH . '/partials_tml';
  return $args;
} );


/**
 * Custom avatar html for plotter
 */
add_filter( 'get_avatar', function( $avatar, $id_or_email, $size, $default, $alt, $args ) {
  // var_dump( esc_html( $avatar ), $id_or_email, $size, $default, $alt, $args );
  if ( ! empty( $args['extra_attr'] ) && 'no-classes' === $args['extra_attr'] ) {
    $avatar = '<img src="'. $args['url'] .'" width="'. $size .'" height="'. $size .'" alt="'. $alt .'" class="" />';
  }
  return $avatar;
}, PHP_INT_MAX, 6 );


/**
 * debug_code
 */
add_action( 'wp_footer', function() {
  $debug_logs = [];
  if ( WP_DEBUG ) {
    global $template; // , $wp_query;
    $_plotter = get_query_var( 'plotter' );
    $template_name = basename( $template, '.php' );
    $log_style = [ "'color: red; font-weight: bold'", "'color: blue; font-weight: bold'" ];
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
    // $_hash = __ctl( 'lib' )::custom_hash( date("Y-m-d H:i:s") );
    // $debug_logs[] = "logger.debug('Current hash: %c{$_hash}', {$log_style[1]});";
    if ( session_status() == PHP_SESSION_ACTIVE ) {
      $debug_logs[] = "logger.debug( JSON.parse('". json_encode( $_plotter ) ."') );";
    }
  } else {
    if ( session_status() == PHP_SESSION_ACTIVE && ! is_front_page() ) {
      // $debug_logs[] = '';
    }
  }
  if ( ! empty( $debug_logs ) ) {
    echo '<script>' . implode( PHP_EOL, $debug_logs ) . '</script>' . PHP_EOL;
  }
}, PHP_INT_MAX );
