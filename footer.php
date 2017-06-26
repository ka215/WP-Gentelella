<?php
/**
 * The Footer for WP-Gentelella theme.
 *
 * @package WP-Gentelella
 */
$page_type = get_tml_pageinfo();

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
<?php
    }
    break;
}
wp_footer();
?>
  </body>
</html>