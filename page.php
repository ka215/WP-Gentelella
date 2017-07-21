<?php
/**
 * The page template file
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */
$page_type = __ctl( 'lib' )::get_pageinfo();
$is_fullwidth = in_array( $page_type, array( 'login', 'lostpassword', 'register' ) );

get_header();

if ( ! $is_fullwidth ) {

  get_sidebar();

  get_template_part( 'partials/top-navi' );

  $template_name = $page_type;
} else {
  $template_name = 'onecol';
}

if ( have_posts() ) {
  while ( have_posts() ) : the_post();
    get_template_part( 'partials/content', $template_name );
  endwhile;
} else {
  get_template_part( 'partials/content', $template_name );
}

get_footer();