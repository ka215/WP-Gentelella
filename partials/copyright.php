<?php
/**
 * Template part for displaying copyright as inline-footer
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */

$page_type = get_pageinfo();

switch ( $page_type ) {
  case 'login':
  case 'register':
  case 'lostpassword':
    $headline_level = 'h2';
    break;
  default:
    $headline_level = 'h4';
    break;
}
?>
<div>
  <<?php echo $headline_level; ?>><i class="plt-quill3"></i> <?php bloginfo( 'name' ); ?></<?php echo $headline_level; ?>>
  <p><i class="fa fa-copyright"></i><?php echo date_i18n( 'Y' ); ?> <?php _e( 'All Rights Reserved.', WPGENT_DOMAIN ); ?></p>
</div>