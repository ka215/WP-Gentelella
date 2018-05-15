<?php
/**
 * The Header for Plotter theme.
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$page_type = get_current_page_type();
$add_classes = [];
$prepend_contents = [];
if ( ! is_user_logged_in() && in_array( $page_type, FULLSPAN_PAGES, true ) ) {
  $add_classes[] = 'full-span';
  if ( ! in_array( $page_type, [ 'home', 'error404', 'thanks' ], true ) ) {
    $add_classes[] = 'login';
  }
} else
if ( 'home' === $page_type ) {
  $add_classes[] = 'full-span';
} else {
  $add_classes[] = isset( $_COOKIE['current_sidebar'] ) && trim( $_COOKIE['current_sidebar'] ) === 'small' ? 'nav-sm' : 'nav-md';
  $prepend_contents[] = "\t<div class=\"container body\">";
  $prepend_contents[] = "\t  <div class=\"main_container\">";
}
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
  <body <?php body_class( implode( ' ', $add_classes ) ); ?>>
<?php echo implode( PHP_EOL, $prepend_contents ); ?>
