<?php
/**
 * Template part for displaying edit-storyline content in page.php
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
$current_structures  = $_plotter['current_structures'];
if ( empty( $current_structures ) ) {
  wp_safe_redirect( '/create-new/' );
}
$current_dependency  = isset( $_COOKIE['dependency'] ) ? (int) $_COOKIE['dependency'] : 0;
//var_dump( __ctl( 'lib' )::get_dependency() );
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
                    <h3><?php _e( 'Edit Storyline', WPGENT_DOMAIN ) ?></h3>
                    <?php get_template_part( 'partials/toolbox' ); ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="structureSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                      <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                      <input type="hidden" name="source_id" value="<?= esc_attr( $current_source_id ) ?>">
                      <input type="hidden" name="dependency" value="<?= esc_attr( $current_dependency ) ?>">
                      <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                      <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>
<?php /* Start: Wizard */ ?>
                      <p class="font-gray-dark">
                        <?php _e( 'Editing of the storyline, you should be done per the storyline on the horizontal line having the same dependency.', WPGENT_DOMAIN ); ?>
                      </p>
                      <div id="wizard" class="form_wizard wizard_horizontal">
                        <div id="parent-step" class="step_relational wizard_prefix<?php if ( $current_dependency == 0 ) : ?> non-parent<?php endif; ?>">
<?php if ( $current_dependency == 0 ) : ?>
                          <label class="root-dependency"><?php _e('Main Storyline', WPGENT_DOMAIN ); ?></label>
<?php else : ?>
                          <ul class="wizard_steps">
                            <li><a href="#">Parent Storyline</a></li>
                          </ul>
<?php endif; ?>
                        </div>
                        <div class="wizard_steps_container">
                          <ul class="wizard_steps">
<?php $_step_order = 0; $_child_step_order = 0; ?>
<?php foreach ( $current_structures as $_idx => $_structure ) : 
        if ( $_structure['dependency'] == $current_dependency ) {
            $_step_order = $_structure['turn'] * 10;
            if ( $_structure['turn'] == 1 ) {
                $_first_view_structure = $_structure;
            } ?>
                            <li data-structure-id="<?= esc_attr( $_structure['id'] ) ?>" data-step="<?= esc_attr( $_idx + 1 ) ?>" style="order: <?= esc_attr( $_step_order ) ?>">
                              <div class="step_indicator<?php if ( $_structure['turn'] == 1 ) : ?> selected<?php endif; ?>">
                                <a href="#act-form" class="step_no"><?= esc_html( $_idx + 1 ) ?></a>
                                <ul class="step_meta">
                                  <li class="step_name"><?= esc_html( $_structure['name'] ) ?></li>
                                </ul>
<?php       if ( $_structure['turn'] > 1 ) : ?>
                                <button type="button" class="btn btn-round btn-default btn-sm btn-remove-act" title="<?php _e('Remove Act', WPGENT_DOMAIN ); ?>" data-target-id="<?= esc_attr( $_structure['id'] ) ?>"><i class="fa fa-close"></i></button>
<?php       endif; ?>
                              </div>
                              <div class="step_relational wizard_vertical">
                                <ul class="wizard_steps">
<?php       foreach ( $current_structures as $_order => $_child_structure ) : 
              if ( $_child_structure['dependency'] == $_structure['id'] && $_child_structure['turn'] == 1 ) : 
                  $_child_step_order = $_order; ?>
                                  <li data-structure-id="<?= esc_attr( $_child_structure['id'] ) ?>" data-group-id="<?= esc_attr( $_child_structure['group_id'] ) ?>" style="order: <?= esc_attr( $_order ) ?>"><a href="#"><?= esc_html( $_child_structure['name'] ) ?></a></li>
<?php         endif;
            endforeach; ?>
                                  <li style="order: <?= esc_attr( $_order + 1 ) ?>"><a href="#" class="add_sub"><?php _e('Add Sub Storyline', WPGENT_DOMAIN ); ?></a></li>
                                </ul>
                              </div>
                            </li>
<?php   };
      endforeach; ?>
                            <li style="order: <?= esc_attr( $_step_order * 10 ) ?>">
                              <div class="step_indicator add_new">
                                <a href="#act-new" class="step_no"><i class="fa fa-plus"></i></a>
                                <ul class="step_meta">
                                  <li class="step_name"><?php _e('Add New', WPGENT_DOMAIN ); ?></li>
                                </ul>
                              </div>
                              <div class="step_relational"></div>
                            </li>
                          </ul><!-- /.wizard_steps -->
                        </div><!-- /.wizard_steps_container -->

                        <div id="act-form">
                          <div class="form-horizontal form-label-left" id="act-form-current">
                            <input type="hidden" id="act-structure-id" name="structure_id" value="<?= esc_attr( $_first_view_structure['structure_id'] ) ?>">
                            <input type="hidden" id="act-turn" name="turn" value="<?= esc_attr( $_first_view_structure['turn'] ) ?>">
                            <input type="hidden" id="act-diff" name="diff" value="true">
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-name"><?php _e('Act Name', WPGENT_DOMAIN ); ?> <span class="required"></span>
                              </label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="act-name" name="name" required="required" class="form-control col-md-7 col-xs-12" placeholder="<?php _e('Act Name', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $_first_view_structure['name'] ) ?>">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-dependency"><?php _e('Dependency', WPGENT_DOMAIN ); ?> <span class="required"></span>
                              </label>
                              <div class="col-md-6 col-sm-9 col-xs-12">
                                <select id="act-dependency" name="dependency" required="required" class="form-control col-md-7 col-xs-12" disabled>
                                  <option value="0" <?php selected( 0, $_first_view_structure['dependency'] ); ?>><?php _e( 'None', WPGENT_DOMAIN ) ?></option>
<?php foreach ( $current_structures as $_structure ) : ?>
                                  <option value="<?= esc_attr( $_structure['id'] ) ?>" <?php selected( $_structure['id'], $_first_view_structure['dependency'] ); ?>><?= esc_html( $_structure['name'] ) ?></option>
<?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-turn"><?php _e('Previous Act', WPGENT_DOMAIN ); ?> <span class="required"></span>
                              </label>
                              <div class="col-md-6 col-sm-9 col-xs-12">
                                <select id="act-turn" name="turn" required="required" class="form-control col-md-7 col-xs-12">
                                  <option value="0" <?php selected( 0, $_first_view_structure['dependency'] ); ?>><?php _e( 'None', WPGENT_DOMAIN ) ?></option>
<?php foreach ( $current_structures as $_structure ) : 
          if ( $_structure['dependency'] == $current_dependency ) : ?>
                                  <option value="<?= esc_attr( $_structure['turn'] ) ?>" <?php selected( $_structure['id'], $_first_view_structure['id'] ); ?>><?= esc_html( $_structure['name'] ) ?></option>
<?php     endif;
      endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-context"><?php _e('Context', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea id="act1-context" name="context" class="form-control col-md-7 col-xs-12" rows="8" placeholder="<?php _e('Explanation of this act etc.', WPGENT_DOMAIN ); ?>"><?= esc_html( $_first_view_structure['context'] ) ?></textarea>
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
                          <li data-structure-id="%s" data-step="%d" style="order: %d">
                            <div class="step_indicator">
                              <a href="javascript:;" class="step_no">%d</a>
                              <ul class="step_meta">
                                <li class="step_name"><?php _e('Act', WPGENT_DOMAIN ); ?> %d</li>
                              </ul>
                              <button type="button" class="btn btn-round btn-default btn-sm btn-remove-act hide" title="<?php _e('Remove Act', WPGENT_DOMAIN ); ?>" data-target-id="%N">
                                <i class="fa fa-close"></i>
                              </button>
                            </div>
                            <div class="step_relational wizard_vertical">
                              <ul class="wizard_steps">
                                <li><a href="#" class="add_sub"><?php _e('Add Sub Storyline', WPGENT_DOMAIN ); ?></a></li>
                              </ul>
                            </div>
                          </li>
                        </ul>
                        <ul class="last-step-template">
                          <li data-step="last" style="order: %d">
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
<?php /* End: Smart Wizard */ ?>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                          <button class="btn btn-default" type="button" id="<?= esc_attr( $page_name ) ?>-btn-cancel"><?php _e( 'Cancel', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-primary" type="button" id="<?= esc_attr( $page_name ) ?>-btn-remove"><?php _e( 'Remove', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-success onValid" type="button" id="<?= esc_attr( $page_name ) ?>-btn-update"><?php _e( 'Commit', WPGENT_DOMAIN ); ?></button>
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

