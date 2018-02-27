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

//get_template_part( 'partials/content' );
?>

<div class="center-block">
  <h2><i class="plt-quill3"></i> Plotter</h2>
  <p>- Static Front Page -</p>
  
  <ul>
    <li><a href="<?= wp_login_url(); ?>" title="<?php _e( 'Sign In', WPGENT_DOMAIN ); ?>"><?php _e( 'Sign In', WPGENT_DOMAIN ); ?></a></li>
    <li><a href="<?= wp_login_url(); /* wp_registration_url(); */ ?>#signup" title="<?php _e( 'Create Account', WPGENT_DOMAIN ); ?>"><?php _e( 'Create Account', WPGENT_DOMAIN ); ?></a></li>
  </ul>
</div>

<?php get_footer();
