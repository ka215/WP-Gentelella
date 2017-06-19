<?php
/**
 * Template part for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */

?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div id="post-<?php the_ID() ?>" <?php post_class(); ?>>
<?php /*
            <div class="page-title">
              <div class="title_left">
                <h3><?php the_title(); ?></h3>
              </div>
            </div>

            <div class="clearfix"></div>
*/ ?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php the_title(); ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<?php
  the_content();
/*
  wp_link_pages( array(
    'before' => '<div class="page-links">' . __( 'Pages:', 'wpgentelella' ),
    'after'  => '</div>',
  ) );
*/ ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.right_col -->

