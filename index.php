<?php
/**
 * The front page template file
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */

get_header();
?>

<?php get_sidebar(); ?>

<?php get_template_part( 'partials/top-navi' ); ?>

<?php // Show the selected frontpage content.
  if ( have_posts() ) :
    while ( have_posts() ) : the_post();
      get_template_part( 'partials/content' );
    endwhile;
  else : // I'm not sure it's possible to have no posts when this page is shown, but WTH.
    get_template_part( 'partials/content' );
  endif; ?>

<?php
  get_footer();
