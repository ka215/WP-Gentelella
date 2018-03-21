<?php
/**
 * Template part for displaying create-new content in page.php
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
$current_structures  = isset( $_plotter['current_structures'] ) ? $_plotter['current_structures'] : [];
if ( ! empty( $current_structures ) ) {
  wp_safe_redirect( '/edit-storyline/' );
}
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
<?php /*
            <div class="page-title">
              <div class="title_left">
                <h2><?php echo esc_html( $current_source_name ) ?></h2>
              </div>
            </div>
            <div class="clearfix"></div>
*/ ?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h3><?php _e( 'Create New Storyline', WPGENT_DOMAIN ) ?></h3>
                    <?php get_template_part( 'partials/toolbox' ); ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="structureSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                      <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                      <input type="hidden" name="source_id" value="<?= esc_attr( $current_source_id ) ?>">
                      <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                      <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>

<?php /* Start: Wizard */ ?>
                      <p class="font-gray-dark">
                        <?php __( 'You should be setting on the form wizard in follow to define the structures from the selected storyline type.', WPGENT_DOMAIN ); ?>
                        <?php _e( 'Firstly, would you choose the storyline type of your main story? Then you should be setting detail of each acts.', WPGENT_DOMAIN ); ?>
                      </p>
<?php if ( empty( $current_structures ) ) : ?>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="structure-presets"><?php _e( 'Storyline Type', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" id="structure-presets" name="structure_type">
                            <option value="0" data-acts="['']" selected="selected"><?php _e( 'Custom Structure', WPGENT_DOMAIN ); ?></option>
                            <option value="3" data-acts="['<?php _e('Set-up', WPGENT_DOMAIN ); ?>','<?php _e('Confrontation', WPGENT_DOMAIN ); ?>','<?php _e('Resolution', WPGENT_DOMAIN ); ?>']"><?php _e( 'Three-act Structure', WPGENT_DOMAIN ); ?></option>
                            <option value="4" data-acts="['<?php _e('Set-up', WPGENT_DOMAIN ); ?>','<?php _e('Confrontation', WPGENT_DOMAIN ); ?>','<?php _e('Resolution', WPGENT_DOMAIN ); ?>','<?php _e('Afterwards', WPGENT_DOMAIN ); ?>']"><?php _e( 'Four-act Structure', WPGENT_DOMAIN ); ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
<?php endif; ?>

                      <div id="wizard" class="form_wizard wizard_horizontal">
                        <div class="wizard_steps_container">
                          <ul class="wizard_steps">
                            <li data-step="1">
                              <div class="step_indicator selected">
                                <a href="javascript:;" class="step_no">1</a>
                                <ul class="step_meta">
                                  <li class="step_name"><?php printf( __( 'Act %d', WPGENT_DOMAIN ), 1 ); ?></li>
                                </ul>
                                <button type="button" class="btn btn-round btn-default btn-sm btn-remove-act hide" title="<?php _e('Remove Act', WPGENT_DOMAIN ); ?>"><i class="fa fa-close"></i></button>
                              </div>
                              <div class="step_relational wizard_vertical"></div>
                            </li>
                            <li data-step="last">
                              <div class="step_indicator add_new">
                                <a href="javascript:;" class="step_no"><i class="fa fa-plus"></i></a>
                                <ul class="step_meta">
                                  <li class="step_name"><?php _e('Add New', WPGENT_DOMAIN ); ?></li>
                                </ul>
                              </div>
                              <div class="step_relational wizard_vertical"></div>
                            </li>
                          </ul><!-- /.wizard_steps -->
                        </div><!-- /.wizard_steps_container -->

                        <div id="act-form">
                          <div class="form-horizontal form-label-left" id="act-form-current">
                            <input type="hidden" id="act-structure-id" name="structure_id" value="">
                            <input type="hidden" id="act-dependency" name="dependency" value="0">
                            <input type="hidden" id="act-turn" name="turn" value="1">
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-name"><?php _e('Act Name', WPGENT_DOMAIN ); ?> <span class="required"></span>
                              </label>
                              <div class="col-md-7 col-sm-9 col-xs-12">
                                <input type="text" id="act-name" name="name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e('Act Name', WPGENT_DOMAIN ); ?>" value="<?php printf( __( 'Act %d', WPGENT_DOMAIN ), 1 ); ?>" required="required">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-context"><?php _e('Context', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea id="act-context" name="context" class="form-control col-md-7 col-xs-12" rows="8" placeholder="<?php _e('Explanation of this act etc.', WPGENT_DOMAIN ); ?>"></textarea>
                              </div>
                            </div>
                            <div class="form-group hide">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-sub-stories"><?php _e('Sub Stories', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                              </div>
                            </div>
                            <div class="form-group hide">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-sequences"><?php _e('Connected Sequences', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                              </div>
                            </div>
                            <div class="form-group hide">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-scenes"><?php _e('Connected Scenes', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                              </div>
                            </div>
                          </div><!-- /#act-form-current -->
                        </div><!-- /#act-form -->
                      </div><!-- /#wizard -->

                      <div id="wizard-templates" class="hide">
                        <ul class="common-step-template">
                          <li data-step="%N">
                            <div class="step_indicator">
                              <a href="javascript:;" class="step_no">%N</a>
                              <ul class="step_meta">
                                <li class="step_name"><?php _e('Act', WPGENT_DOMAIN ); ?> %N</li>
                              </ul>
                              <button type="button" class="btn btn-round btn-default btn-sm btn-remove-act hide" title="<?php _e('Remove Act', WPGENT_DOMAIN ); ?>">
                                <i class="fa fa-close"></i>
                              </button>
                            </div>
                            <div class="step_relational wizard_vertical"></div>
                          </li>
                        </ul>
                        <ul class="last-step-template">
                          <li data-step="last">
                            <div class="step_indicator add_new">
                              <a href="javascript:;" class="step_no"><i class="fa fa-plus"></i></a>
                              <ul class="step_meta">
                                <li class="step_name"><?php _e('Add New', WPGENT_DOMAIN ); ?></li>
                              </ul>
                            </div>
                            <div class="step_relational wizard_vertical"></div>
                          </li>
                        </ul>
                      </div><!-- /#wizard-templates -->
<?php /* End: Wizard */ ?>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                          <button class="btn btn-default" type="button" id="<?= esc_attr( $page_name ) ?>-btn-reset"><?php _e( 'Reset', WPGENT_DOMAIN ); ?></button>
<?php /*
                          <button class="btn btn-primary" type="button" id="<?= esc_attr( $page_name ) ?>-btn-remove"><?php _e( 'Remove', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-success onValid" type="button" id="<?= esc_attr( $page_name ) ?>-btn-update"><?php _e( 'Update', WPGENT_DOMAIN ); ?></button>
*/ ?>
                          <button class="btn btn-primary onValid" type="button" id="<?= esc_attr( $page_name ) ?>-btn-create"   ><?php _e( 'Create', WPGENT_DOMAIN ); ?></button>
                        </div>
                      </div><!-- /.form-group -->

                    </form><!-- /#structureSettings -->
                  </div><!-- /.x_content -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.right_col -->

