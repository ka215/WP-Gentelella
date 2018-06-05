<?php
/**
 * Template part for displaying user-policies content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$page_type = get_current_page_type();
$page_data = get_current_page_data( $page_type );

if ( is_user_logged_in() ) :
?>
<div class="right_col" role="main">
  <div <?php post_class( 'flex-container' ); ?>>
<?php /*
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12"> */ ?>
    <div class="x_panel panel-primary service" id="<?= $page_type ?>">
      <div class="x_title">
        <h2><?= $page_data['title'] ?></h2>
<?php if ( isset( $page_data['modified'] ) ) : ?>
        <div class="x_title_meta pull-right">
          <span><?= date( 'M jS, Y', strtotime( $page_data['modified'] ) ) ?></span>
        </div>
<?php endif; ?>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <?= $page_data['content'] ?>
      </div>
    </div><!-- /.x_panel -->
<?php /*
    </div><!-- /.col -->
  </div><!-- /.row --> */ ?>
  </div><!-- /.flex-container -->
</div>
<!-- /.right_col -->
<?php else : ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 service" id="<?= $page_type ?>">
      <div class="header-container">
        <h2 class="h2"><?= $page_data['title'] ?></h2>
<?php if ( isset( $page_data['modified'] ) ) : ?>
        <div class="header-meta pull-right">
          <span><?= date( 'M jS, Y', strtotime( $page_data['modified'] ) ) ?></span>
        </div>
<?php endif; ?>
      </div>
      <div class="body-content">
        <?= $page_data['content'] ?>
      </div>
      <div class="clearfix"></div>
      <div class="footer-container">
        <a href="<?= home_url( '/' ) ?>" class="btn btn-default"><?= __( 'Back to Top', WPGENT_DOMAIN ) ?></a>
      </div>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
<?php endif;
