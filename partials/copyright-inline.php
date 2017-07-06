<?php
/**
 * Template part for displaying copyright as inline-footer
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */
?>
<div>
  <strong><i class="plt-quill3"></i> <?php bloginfo( 'name' ); ?></strong>
  <i class="fa fa-copyright"></i><?php echo date_i18n( 'Y' ); ?> <?php _e( 'All Rights Reserved.', WPGENT_DOMAIN ); ?>
</div>