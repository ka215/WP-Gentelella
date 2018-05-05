<?php
/**
 * Template part for displaying copyright
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */

$page_type = get_current_page_type();
$is_large_footer_pages = [
  // Page types that displays with a wide 2 lines
  // 'home'
  'login', 'logout', 'register', 'lostpassword', 'resetpass',
];

$headline = '<i class="plt-quill3"></i> '. get_bloginfo( 'name' );
$bodyline = '<i class="fa fa-copyright"></i>'. date_i18n( 'Y' ) .' '. __( 'All Rights Reserved.', WPGENT_DOMAIN );
if ( in_array( $page_type, $is_large_footer_pages ) ) {
  $headline = '<h2>'. $headline .'</h2>';
  $bodyline = '<p>'. $bodyline .'</p>';
} else {
  $headline = '<strong>'. $headline .'</strong>';
}
?>
<div class="copyright">
  <?= $headline ?>
  <?= $bodyline ?>
</div>
