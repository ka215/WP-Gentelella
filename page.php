<?php
/**
 * The page template file
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$page_type = get_current_page_type();
$is_fullwidth = in_array( $page_type, [
  // full-span only pages
  'login', 'lostpassword', 'register', 'resetpass', 'thanks', 
], true );
$is_both_width = in_array( $page_type, [
  'service', 'user-policies', 'privacy-policy', 'cookie-policy', 
  // 'help',
], true );

get_header();

if ( $is_fullwidth ) {
    switch ( $page_type ) {
        case 'thanks':
            $template_name = $page_type;
            break;
        default:
            $template_name = 'onecol';
            break;
    }
} else
if ( $is_both_width && ! is_user_logged_in() ) {
    $template_name = 'service';
} else {

  get_sidebar();

  get_template_part( 'partials/top-navi' );

  $template_name = $is_both_width ? 'service' : $page_type;
}

if ( have_posts() ) {
  while ( have_posts() ) : the_post();
    get_template_part( 'partials/content', $template_name );
  endwhile;
} else {
  get_template_part( 'partials/content', $template_name );
}

get_footer();