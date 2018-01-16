<?php
/**
 * Template part for displaying create-new content in page.php
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */
$_plotter = get_query_var( 'plotter', [] );
$page_name           = $_plotter['page_name'];
$current_user_id     = $_plotter['current_user_id'];
$user_sources        = $_plotter['user_sources'];
$current_source_id   = $_plotter['current_source_id'];
$current_source_name = $_plotter['current_source_name'];
$current_structures  = $_plotter['current_structures'];
var_dump($current_source_id);
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
            <div class="page-title">
              <div class="title_left">
                <h2><?php echo esc_html( $current_source_name ) ?></h2>
              </div>
            </div>

            <div class="clearfix"></div>

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
<?php if ( empty( $current_structures ) ) : ?>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="structure-presets"><?php _e( 'Storyline Type', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" id="structure-presets" name="structure_type">
                            <option value="custom" data-acts="['']"><?php _e( 'Custom Structure', WPGENT_DOMAIN ); ?></option>
                            <option value="3acts" data-acts="['<?php _e('Set-up', WPGENT_DOMAIN ); ?>','<?php _e('Confrontation', WPGENT_DOMAIN ); ?>','<?php _e('Resolution', WPGENT_DOMAIN ); ?>']"><?php _e( 'Three-act Structure', WPGENT_DOMAIN ); ?></option>
                            <option value="4acts" data-acts="['<?php _e('Set-up', WPGENT_DOMAIN ); ?>','<?php _e('Confrontation', WPGENT_DOMAIN ); ?>','<?php _e('Resolution', WPGENT_DOMAIN ); ?>','<?php _e('Afterwards', WPGENT_DOMAIN ); ?>']"><?php _e( 'Four-act Structure', WPGENT_DOMAIN ); ?></option>
                          </select>
                        </div>
                      </div>
<?php endif; ?>
                      <h5><?php _e( 'Advanced Settings of Storyline', WPGENT_DOMAIN ); ?></h5>
                      <div class="ln_solid"></div>
                      <!-- p class="font-gray-dark">helper text</p -->

<?php /* Start: Smart Wizard */ ?>
                      <p>This is a basic form wizard example that inherits the colors from the selected scheme.</p>
                      <div id="wizard" class="form_wizard wizard_horizontal">
                        <ul class="wizard_steps">
                          <li><a href="#act-1">
                            <span class="step_no">1</span>
                            <span class="step_descr"><?php _e('Act', WPGENT_DOMAIN ); ?> 1<br /><small></small></span>
                            </a>
                            <div class="step-bottom-block">
                              <button class="btn btn-success btn-sm hide" id="btn-addsub-act-1"><i class="fa fa-plus"></i> <?php _e('Sub Story', WPGENT_DOMAIN ); ?></button>
                              <br />
                              <button class="btn btn-default btn-sm hide" id="btn-remove-act-1"><i class="fa fa-close"></i> <?php _e('Remove Act', WPGENT_DOMAIN ); ?></button>
                            </div>
                          </li>
                          <li><a href="#act-new">
                            <span class="step_no"><i class="fa fa-plus"></i></span>
                            <span class="step_descr"><?php _e('Add New', WPGENT_DOMAIN ); ?><br /><small></small></span>
                            </a>
                            <div class="step-bottom-block">
                            </div>
                          </li>
                        </ul><!-- /.wizard_steps -->

                        <div id="act-1">
                          <div class="form-horizontal form-label-left" id="act-form-1">
                            <input type="hidden" id="act1-structure-id" name="act1[structure_id]" value="">
                            <input type="hidden" id="act1-dependency" name="act1[dependency]" value="0">
                            <input type="hidden" id="act1-turn" name="act1[turn]" value="1">
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act1-name"><?php _e('Act Name', WPGENT_DOMAIN ); ?> <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="act1-name" name="act1[name]" required="required" class="form-control col-md-7 col-xs-12" placeholder="<?php _e('Act Name', WPGENT_DOMAIN ); ?>" value="">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act1-context"><?php _e('Context', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="act1-context" name="act1[context]" class="form-control col-md-7 col-xs-12" rows="8" placeholder="<?php _e('Explanation of this act etc.', WPGENT_DOMAIN ); ?>"></textarea>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act1-sub-stories"><?php _e('Sub Stories', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act1-sequences"><?php _e('Connected Sequences', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act1-scenes"><?php _e('Connected Scenes', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                              </div>
                            </div>
                          </div><!-- /#act-form-1 -->
                        </div><!-- /#act-1 -->
                        <div id="act-new">
                        </div>

                      </div><!-- /#wizard -->
                      <div id="wizard-templates" class="hide">
                        <ul class="wizard-step-template">
                          <li><a href="#act-%N">
                            <span class="step_no">%N</span>
                            <span class="step_descr"><?php _e('Act', WPGENT_DOMAIN ); ?> %N<br /><small></small></span>
                            </a>
                            <div class="step-bottom-block">
                              <button class="btn btn-success btn-sm hide" id="btn-addsub-act-%N"><i class="fa fa-plus"></i> <?php _e('Sub Story', WPGENT_DOMAIN ); ?></button>
                              <br />
                              <button class="btn btn-default btn-sm hide" id="btn-remove-act-%N"><i class="fa fa-close"></i> <?php _e('Remove Act', WPGENT_DOMAIN ); ?></button>
                            </div>
                          </li>
                        </ul><!-- /.wizard-step-template -->
                        <div class="wizard-act-template">
                          <div id="act-%N">
                            <div class="form-horizontal form-label-left" id="act-form-%N">
                              <input type="hidden" id="act%N-structure-id" name="act%N[structure_id]" value="">
                              <input type="hidden" id="act%N-dependency" name="act%N[dependency]" value="0">
                              <input type="hidden" id="act%N-turn" name="act%N[turn]" value="%N">
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act%N-name">Act Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="text" id="act%N-name" name="act%N[name]" required="required" class="form-control col-md-7 col-xs-12" placeholder="<?php _e('Act Name', WPGENT_DOMAIN ); ?>">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act%N-context"><?php _e('Context', WPGENT_DOMAIN ); ?></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <textarea id="act%N-context" name="act%N[context]" class="form-control col-md-7 col-xs-12" rows="8" placeholder="<?php _e('Explanation of this act etc.', WPGENT_DOMAIN ); ?>"></textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act%N-sequences"><?php _e('Connected Sequences', WPGENT_DOMAIN ); ?></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="act%N-scenes"><?php _e('Connected Scenes', WPGENT_DOMAIN ); ?></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <ul class="list-inline"><li><?php _e('None', WPGENT_DOMAIN ); ?></li></ul>
                                </div>
                              </div>
                            </div><!-- /#act-form-%N -->
                          </div><!-- /#act-%N -->
                        </div><!-- /.wizard-act-template -->
                      </div><!-- /#wizard-templates -->
<?php /* End: Smart Wizard */ ?>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                          <button class="btn btn-default" type="button" id="<?= esc_attr( $page_name ) ?>-btn-cancel"><?php _e( 'Cancel', WPGENT_DOMAIN ); ?></button>
<?php /*
                          <button class="btn btn-primary" type="button" id="<?= esc_attr( $page_name ) ?>-btn-remove"><?php _e( 'Remove', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-success onValid" type="button" id="<?= esc_attr( $page_name ) ?>-btn-update"><?php _e( 'Update', WPGENT_DOMAIN ); ?></button>
*/ ?>
                          <button class="btn btn-success onValid" type="button" id="<?= esc_attr( $page_name ) ?>-btn-create"   ><?php _e( 'Create', WPGENT_DOMAIN ); ?></button>
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

