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
          <div <?php post_class( 'flex-container' ); ?>>
<?php /*
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"> */ ?>
            <div class="x_panel panel-primary">
              <div class="x_title">
                <h3><i class="plt-cog3 blue"></i> <?= __( 'Plotter Settings', WPGENT_DOMAIN ) ?></h3>
                <?php get_template_part( 'partials/toolbox' ); ?>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form id="generalSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                  <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                  <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                  <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>

                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="item_name"><?= __( 'Option Name', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="item_name" name="item_name" class="form-control col-md-7 col-xs-12" placeholder="<?= __( 'Placeholder Text', WPGENT_DOMAIN ) ?>" value="<?= esc_attr( '' ) ?>" required="required">
                    </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                      <button type="button" class="btn btn-primary"><?= __( 'Save Changes', WPGENT_DOMAIN ) ?></button>
                    </div>
                  </div>
                </form>
              </div><!-- /.x_content -->
            </div><!-- /.x_panel -->
<?php /*
            </div><!-- /.col -->
          </div><!-- /.row --> */ ?>
          </div><!-- /.flex-container -->
        </div>
        <!-- /.right_col -->

