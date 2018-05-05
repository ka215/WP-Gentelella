<?php
/**
 * Template part for displaying settings content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$_plotter = get_query_var( 'plotter', [] );
$page_name       = @$_plotter['page_name'] ?: '';
$current_user_id = @$_plotter['current_user_id'] ?: null;
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?= __( 'Plotter Settings', WPGENT_DOMAIN ) ?></h2>
                    <?php get_template_part( 'partials/toolbox' ); ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="generalSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                      <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                      <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                      <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>
                      
                      
                      <button type="button" class="btn btn-primary"><?= __( 'Save Changes', WPGENT_DOMAIN ) ?></button>
                      <button type="button" class="btn btn-dark"><?= __( 'Delete Account', WPGENT_DOMAIN ) ?></button>
                      <p class="help-block"></p>
                    </form>
                  </div><!-- /.x_content -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.right_col -->

