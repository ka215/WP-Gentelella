<?php
/**
 * Template part for displaying common content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$enable_toolbox = false;
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class( 'flex-container' ); ?>>
<?php /*
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"> */ ?>
            <div class="x_panel panel-primary">
              <div class="x_title">
                <h2><?php the_title(); ?></h2>
<?php if ( $enable_toolbox ) : ?>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#"><?= __( 'Settings', WPGENT_DOMAIN ) ?> 1</a></li>
                      <li><a href="#"><?= __( 'Settings', WPGENT_DOMAIN ) ?> 2</a></li>
                    </ul>
                  </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
<?php endif; ?>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
<?php
    the_content();
    wp_link_pages( [
        'before' => '<div class="page-links">' . __( 'Pages:', WPGENT_DOMAIN ),
        'after'  => '</div>',
    ] );
?>
              </div>
            </div>
          </div>
<?php /*
            </div><!-- /.col -->
          </div><!-- /.row --> */ ?>
        </div>
        <!-- /.right_col -->

