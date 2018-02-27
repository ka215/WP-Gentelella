<?php
/**
 * The Header for Plotter theme.
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
  <head>
    <meta charset="<?= bloginfo('charset') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php wp_head(); ?>
  </head>

<?php
$page_type = __ctl( 'lib' )::get_pageinfo();
$prepend_contents = [];
switch ( $page_type ) {
  case 'login':
  case 'register':
  case 'lostpassword':
    $add_class = 'login';
    $prepend_contents[] = '    <div>';
    $prepend_contents[] = '      <a class="hiddenanchor" id="signup"></a>';
    $prepend_contents[] = '      <a class="hiddenanchor" id="signin"></a>';
    break;
  default:
    $add_class = isset( $_COOKIE['current_sidebar'] ) && trim( $_COOKIE['current_sidebar'] ) === 'small' ? 'nav-sm' : 'nav-md';
    $prepend_contents[] = '    <div class="container body">';
    $prepend_contents[] = '      <div class="main_container">';
    break;
}
?>
  <body <?php body_class( $add_class ); ?>>
<?php echo implode( PHP_EOL, $prepend_contents ); ?>

