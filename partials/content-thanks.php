<?php
/**
 * Template part for displaying thanks content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="jumbotron">
        <div class="container">
          <h2 class="h2 text-center"><?= __( 'Thank you for using so far.', WPGENT_DOMAIN ) ?></h2>
          <p class="text-center">
            <?= __( 'Your account has been deleted successfully.', WPGENT_DOMAIN ) ?><br>
            <?= __( 'You are welcome to again if you have the opportunity.', WPGENT_DOMAIN ) ?><br>
          </p>
        </div>
      </div>
      
      <div class="text-center">
        <a href="<?= home_url( '/' ) ?>" target="_top"><?= __( 'Back to Top', WPGENT_DOMAIN ) ?></a>
      </div>
      
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
