<?php
/**
 * Template part for displaying dashboard content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$_plotter = get_query_var( 'plotter', [] );
$page_name           = $_plotter['page_name'];
$current_user_id     = $_plotter['current_user_id'];
$user_sources        = $_plotter['user_sources'];
$current_source_id   = $_plotter['current_source_id'];
$current_source_name = $_plotter['current_source_name'];
$user_approval_state = $_plotter['approval_state'];
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
<?php if ( __ctl( 'lib' )::is_first_visit() ) : ?>
            <div class="page-title">
              <div class="title_left">
                <h2><?= __( 'Welcome Plotter!', WPGENT_DOMAIN ) ?></h2>
              </div>
            </div>
            <div class="clearfix"></div>
<?php endif; ?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h3><?php if ( empty( $user_sources ) ) {
                        _e( "First of all, let's enter the title of your story.", WPGENT_DOMAIN );
                    } else {
                        _e( "Let's get started!", WPGENT_DOMAIN );
                    } ?></h3>
                    <?php get_template_part( 'partials/toolbox' ); ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="initialSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                      <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                      <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>
<?php if ( empty( $user_sources ) ) : ?>
                      <p><?php _e( 'Even an unsettled title is fine. This title of the story can be edited after registering.', WPGENT_DOMAIN ); ?></p>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_name"><?php _e( 'Title Of Story', WPGENT_DOMAIN ); ?> <span class="required"></span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="source_name" name="source_name" class="form-control" placeholder="<?php _e( 'Your Story Title', WPGENT_DOMAIN ); ?>" required="required">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <button type="submit" class="btn btn-success" id="<?= esc_attr( $page_name ) ?>-submit"><?php _e( 'Register', WPGENT_DOMAIN ); ?></button>
                        </div>
                      </div>
<?php else : the_content(); endif; ?>
<?php if ( ! $user_approval_state ) : ?>
                        <input type="hidden" name="approve_user_policy" value="true" />
<?php endif; ?>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.right_col -->
<?php if ( ! $user_approval_state ) : ?>
        <div id="user-policy" class="modal fade" tabindex="1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4><?= __( 'The User Policy', WPGENT_DOMAIN ); ?></h4>
              </div><!-- /.modal-header -->
              <div id="user-policy-container" class="modal-body">
<?php var_dump( 'etc...' ); ?>
              </div><!-- /.modal-body -->
              <div class="modal-footer">
                <button type="button" class="btn btn-dark" id="unapprove-user-policy" data-redirect-url="<?= wp_logout_url(); ?>"><?= __( 'Unapprove', WPGENT_DOMAIN ) ?></button>
                <button type="button" class="btn btn-primary" id="approve-user-policy" _data-dismiss="modal"><?= __( 'Approve', WPGENT_DOMAIN ) ?></button>
              </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
<?php endif; ?>
