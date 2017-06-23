<?php
/**
 * The front page template file
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */

get_header();

//get_template_part( 'partials/content' );
?>

<h2>Static Front Page for Plotter</h2>

<ul>
  <li><a href="<?= wp_login_url(); ?>" title="Sign In">Sign In</a></li>
  <li><a href="<?= wp_registration_url(); ?>" title="Sign Up">Sign Up</a></li>
</ul>

<?php get_footer();
