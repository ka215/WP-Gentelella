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

if ( ! is_user_logged_in() && in_array( $page_type, FULLSPAN_PAGES ) ) {
?>
        <!-- footer content -->
        <footer role="contentinfo">
          <div class="copyright">
            <?php get_template_part( 'partials/copyright' ); ?>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
<?php 
} else {
?>
        <!-- footer content -->
        <footer role="contentinfo">
          <div class="copyright pull-right">
            <?php get_template_part( 'partials/copyright' ); ?>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

      </div><!-- /.main_container -->
    </div><!-- /.container.body -->
<?php 
}
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