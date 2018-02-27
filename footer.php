<?php
/**
 * The Footer for Plotter theme.
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$page_type = __ctl( 'lib' )::get_pageinfo();

switch ( $page_type ) {
  case 'login':
  case 'register':
  case 'lostpassword':
    echo '</div>' . PHP_EOL;
    break;
  default:
    if ( is_front_page() ) {
?>

      </div>
      <!-- /.main_container -->
    </div>
    <!-- /.container.body -->
<?php
    } else {
?>
        <!-- footer content -->
        <footer role="contentinfo">
          <div class="copyright pull-right">
            <?php get_template_part( 'partials/copyright', 'inline' ); ?>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

      </div>
      <!-- /.main_container -->
    </div>
    <!-- /.container.body -->
    
    <div id="custom_notifications" class="custom-notifications dsp_none">
      <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
      </ul>
      <div class="clearfix"></div>
      <div id="notif-group" class="tabbed_notifications"></div>
    </div>
    <!-- /.custom-notifications -->
    
<?php
    }
    break;
}
wp_footer();
?>
  </body>
</html>