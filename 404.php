<?php
/**
 * The 404 page template file
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
get_header();

if ( is_user_logged_in() && ! is_admin() ) :

  get_sidebar();

  get_template_part( 'partials/top-navi' );

?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>404. <?= __( 'Content Not Found.', WPGENT_DOMAIN ) ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?= __( "Sorry, the content you're looking for cannot be found.", WPGENT_DOMAIN ) ?><br/>
                    <?= __( 'Visit our homepage, get help, or try searching.', WPGENT_DOMAIN ) ?><br/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.right_col -->
<?php else : ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="jumbotron">
        <div class="container">
          <h2 class="h2 text-center">404. <?= __( 'Content Not Found.', WPGENT_DOMAIN ) ?></h2>
          <p class="text-center"><?= __( "Something'&#039;s wrong here...", WPGENT_DOMAIN ) ?></p>
        </div>
      </div>
      
      <div class="text-center">
        <a href="<?= home_url( '/' ) ?>" target="_top"><?= __( 'Back to Top', WPGENT_DOMAIN ) ?></a>
      </div>
      
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->

<?php 
endif;

get_footer();