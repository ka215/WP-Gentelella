<?php
/**
 * The Footer for Plotter theme.
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$page_type = get_current_page_type();

$append_html = [];
if ( ! is_user_logged_in() && in_array( $page_type, FULLSPAN_PAGES ) ) {
  $add_class   = '';
} else
if ( in_array( $page_type, FULLSPAN_PAGES ) ) {
  $add_class   = '';
} else {
  $add_class   = 'pull-right';
  $append_html[] = '      </div><!-- /.main_container -->';
  $append_html[] = '    </div><!-- /.container.body -->';
}
?>
        <!-- footer content -->
        <footer role="contentinfo">
          <div class="<?= $add_class ?>">
            <?php get_template_part( 'partials/copyright' ); ?>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
<?php 
echo implode( PHP_EOL, $append_html );

if ( ENABLE_NOTIFICATION ) : ?>
    <div id="custom_notifications" class="custom-notifications dsp_none">
      <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
      </ul>
      <div class="clearfix"></div>
      <div id="notif-group" class="tabbed_notifications"></div>
    </div><!-- /.custom-notifications -->
<?php 
endif;

wp_footer();

?>
  </body>
</html>