<?php
/**
 * The Footer for WP-Gentelella theme.
 *
 * @package WP-Gentelella
 */
$page_type = get_tml_pageinfo();
$append_contents = [];
switch ( $page_type ) {
  case 'login':
    
    echo '</div>' . PHP_EOL;
    break;
  default:
?>
        <!-- footer content -->
        <footer role="contentinfo">
          <div class="copyright pull-right">
            <?php /* echo nl2br( wpgentelella_option( 'copyright' ) ); */ ?>
            <div hidden>
              Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

      </div>
      <!-- /.main_container -->
    </div>
    <!-- /.container.body -->
<?php
    break;
}
wp_footer();
?>
  </body>
</html>