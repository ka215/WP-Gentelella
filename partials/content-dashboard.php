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
$page_name           = @$_plotter['page_name'] ?: '';
$current_user_id     = @$_plotter['current_user_id'] ?: null;
$user_sources        = @$_plotter['user_sources'] ?: [];
$current_source_id   = @$_plotter['current_source_id'] ?: null;
$current_source_name = @$_plotter['current_source_name'] ?: '';
$user_approval_state = @$_plotter['approval_state'] ?: false;
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class( 'flex-container' ); ?>>
<?php if ( __ctl( 'lib' )::is_first_visit() ) : ?>
            <div class="page-title">
              <div class="title_left">
                <h2><?= __( 'Welcome to Plotter!', WPGENT_DOMAIN ) ?></h2>
              </div>
            </div><!-- /.page-title -->
<?php endif; /*
            <div _class="row">
              <div _class="col-md-12 col-sm-12 col-xs-12"> */ ?>
            <div class="x_panel panel-primary">
              <div class="x_title">
                <h3><?php if ( empty( $user_sources ) ) : 
                ?><i class="plt-quill3 blue"></i> <?= __( "Let's weave a new story!", WPGENT_DOMAIN ) ?><?php 
                else : 
                ?><i class="plt-meter3 blue"></i> <?= __( "Summary of your stories", WPGENT_DOMAIN ) ?><?php 
                endif; ?></h3>
                <?php get_template_part( 'partials/toolbox' ); ?>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form id="initialSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                  <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                  <input type="hidden" name="post_action" value="">
                  <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>
<?php if ( $user_approval_state ) {
        if ( empty( $user_sources ) ) { ?>
                  <p><?= __( "First of all, let's enter the title of your story.", WPGENT_DOMAIN ) ?></p>
                  <p><?= __( 'Even an unsettled title is fine. This title of the story can be edited after registering.', WPGENT_DOMAIN ) ?></p>
                  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_name"><?php _e( 'Title Of Story', WPGENT_DOMAIN ); ?> <span class="required"></span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="source_name" name="source_name" class="form-control" placeholder="<?php _e( 'Your Story Title', WPGENT_DOMAIN ); ?>" required="required">
                    </div>
                  </div>
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                      <button type="button" class="btn btn-primary" id="<?= esc_attr( $page_name ) ?>-register"><?php _e( 'Register', WPGENT_DOMAIN ); ?></button>
                    </div>
                  </div>
<?php   } else {
          /* Normal Views */ ?>
                  <div class="table-responsive">
                    <p><?= __( 'The stories you can edit now is as follows. If you want to add a new story you can do from the "Whole Story" on the left menu.', WPGENT_DOMAIN ) ?></p>
                    <table id="summary-stories" class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th><?= __( 'Story Title', WPGENT_DOMAIN ) ?></th>
                          <th class="text-center"><?= __( 'Owner', WPGENT_DOMAIN ) ?></th>
                          <th class="text-center"><?= __( 'Teamwork', WPGENT_DOMAIN ) ?></th>
                          <th class="text-center"><?= __( 'Members', WPGENT_DOMAIN ) ?></th>
                          <th class="text-center"><?= __( 'Last Modified', WPGENT_DOMAIN ) ?></th>
                        </tr>
                      </thead>
                      <tbody>
<?php     foreach ( $user_sources as $_src ) : 
            if ( $current_user_id == $_src['team_data']['owner'] ) {
              $owner_name = __( 'You', WPGENT_DOMAIN );
            } else {
              $_user = get_user_by( 'id', $_src['team_data']['owner'] );
              $owner_name = $_user->display_name;
            } ?>
                        <tr data-src-id="<?= esc_attr( $_src['id'] ) ?>">
                          <th class="text-center"><?php if ( $current_source_id === $_src['id'] ) : ?><i class="plt-book blue"></i><?php endif; ?></th>
                          <td><?= esc_html( $_src['name'] ) ?></td>
                          <td class="text-center"><?= esc_html( $owner_name ) ?></td>
                          <td class="text-center"><i class="<?php if ( $_src['type'] === 1 ) : ?>plt-checkmark green<?php else : ?>plt-minus3 gray<?php endif; ?>"></i></td>
                          <td class="text-center"><?= count( explode( ',', $_src['team_data']['members'] ) ) ?></td>
                          <td class="text-center"><?= $_src['updated_at'] ?></td>
                        </tr>
<?php     endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                  <?php if ( WP_DEBUG ) { the_content(); } ?>
<?php   }
      } else { ?>
                  <input type="hidden" name="approve_user_policy" value="true" />
<?php } ?>
<?php if ( WP_DEBUG ) {
var_dump( $_plotter );
var_dump( __ctl( 'model' )->in_team_work( $current_user_id ) );
var_dump( __ctl( 'model' )->have_sources( null, false ) );
var_dump( __ctl( 'model' )->auth_user_source( 28 ) );
var_dump( __ctl( 'model' )->get_editable_sources() );
} ?>
                </form>
              </div>
            </div>
<?php /*
              </div><!-- /.col -->
            </div><!-- /.row --> */ ?>
          </div><!-- /.flex-container -->
        </div>
        <!-- /.right_col -->
<?php if ( ! $user_approval_state ) : ?>
        <div id="user-policy" class="modal fade" tabindex="1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h2><?= __( 'User Policies', WPGENT_DOMAIN ); ?></h2>
              </div><!-- /.modal-header -->
              <div id="user-policy-container" class="modal-body">
<?php
$page_data = get_current_page_data( 'user-policies' );
echo $page_data['content'];
?>
              </div><!-- /.modal-body -->
              <div class="modal-footer">
                <button type="button" class="btn btn-dark" id="unapprove-user-policy" data-redirect-url="<?= wp_logout_url(); ?>"><?= __( 'Unapprove', WPGENT_DOMAIN ) ?></button>
                <button type="button" class="btn btn-primary" id="approve-user-policy" _data-dismiss="modal"><?= __( 'Approve', WPGENT_DOMAIN ) ?></button>
              </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
<?php endif; ?>
