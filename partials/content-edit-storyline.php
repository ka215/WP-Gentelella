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
// Define template vars as follow:
$page_name           = $_plotter['page_name'];
$current_user_id     = $_plotter['current_user_id'];
$user_sources        = $_plotter['user_sources'];
$current_source_id   = $_plotter['current_source_id'];
$current_source_name = $_plotter['current_source_name'];
$current_structures  = $_plotter['current_structures'];
if ( empty( $current_structures ) ) {
  wp_safe_redirect( '/create-new/' );
}
$current_dependency  = $_plotter['current_dependency'];
$current_group_id    = isset( $_COOKIE['group_id'] ) ? (int) $_COOKIE['group_id'] : null;
if ( empty( $current_group_id ) ) {
  foreach ( $current_structures as $_structure ) {
    if ( $current_dependency == $_structure['dependency'] ) {
      $current_group_id = $_structure['group_id'];
      break;
    }
  }
}
if ( $current_group_id != -1 ) {
    $first_view_context = __ctl( 'model' )->get_structures( 'context', 
        [ 'source_id' => $current_source_id, 'status' => 1, 'dependency' => $current_dependency, 'group_id' => $current_group_id ],
        'and', [ 'turn' => 'asc' ], 1 );
    $first_view_context = __ctl( 'lib' )->array_flatten( $first_view_context )['context'];
} else {
    $first_view_context = '';
}
// For debug
$disp_group_id = WP_DEBUG ? ' <span style="font-size:.75em;">(CurrentGroupId: '. (string) $current_group_id .')</span>' : '';
// var_dump( __ctl( 'libs' )->get_dependent_structures( $current_source_id, $current_dependency, $current_group_id ) );

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
                          <label class="root-dependency"><?php _e('Main Storyline', WPGENT_DOMAIN ); ?><?= $disp_group_id ?></label>
<?php else : 
        foreach ( $current_structures as $_structure ) {
          if ( $_structure['id'] == $current_dependency ) { 
            $_parent_dependency = $_structure['dependency']; ?>
                          <ul class="wizard_steps">
                            <li data-dependency="<?= esc_attr( $_parent_dependency ) ?>"><a href="#" class="parent_storyline"><?= esc_html( $_structure['name'] ) ?><?= $disp_group_id ?></a></li>
                          </ul>
<?php       break;
          }
        }
      endif; ?>
                        </div><!-- /#parent-step -->
<?php if ( $current_dependency != 0 && $current_group_id !== -1 ) : ?>
                        <div id="dependency-selector" class="">
                          <select id="act-dependency" name="new_dependency" class="form-control">
                            <option value="<?= esc_attr( $current_dependency ) ?>" selected="selected">&#x2014; <?php _e('No change with leaving the dependency', WPGENT_DOMAIN ); ?> &#x2014;</option>
<?php   foreach ( $current_structures as $_structure ) { 
          if ( $_parent_dependency == $_structure['dependency'] && $current_dependency != $_structure['id'] ) { ?>
                            <option value="<?= esc_attr( $_structure['id'] ) ?>"><?= esc_html( $_structure['name'] ) ?></option>
<?php     } 
        } ?>
                          </select>
                        </div><!-- /#dependency-selector -->
<?php endif; ?>
                        <div class="clearfix"></div>
                        <div class="wizard_steps_container">
                          <ul class="wizard_steps">
<?php $_step_order = 0; $_child_step_order = 0; $_step_counter = 0;
      foreach ( $current_structures as $_idx => $_structure ) : 
        if ( $_structure['dependency'] == $current_dependency && $_structure['group_id'] == $current_group_id ) {
            $_step_order = $_structure['turn'] * 10;
            $_step_counter++;
            if ( $_structure['turn'] == 1 ) {
                $_first_view_structure = $_structure;
                $_first_view_structure['context'] = $first_view_context;
            } ?>
                            <li data-structure-id="<?= esc_attr( $_structure['id'] ) ?>" data-group-id="<?= esc_attr( $_structure['group_id'] ) ?>" data-step="<?= esc_attr( $_step_counter ) ?>" style="order: <?= esc_attr( $_step_order ) ?>">
                              <div class="step_indicator<?php if ( $_structure['turn'] == 1 ) : ?> selected<?php endif; ?>">
                                <a href="#act-form" class="step_no"><?= esc_html( $_step_counter ) ?></a>
                                <ul class="step_meta">
                                  <li class="step_name"><?= esc_html( $_structure['name'] ) ?></li>
                                </ul>
<?php       if ( $_structure['turn'] > 0 ) : ?>
                                <button type="button" class="btn btn-round btn-default btn-sm btn-remove-act<?php if ( $_structure['turn'] == 1 ) : ?> hide<?php endif; ?>" title="<?php _e('Remove Act', WPGENT_DOMAIN ); ?>" data-target-id="<?= esc_attr( $_structure['id'] ) ?>"><i class="plt-cross2"></i></button>
<?php       endif; ?>
                              </div>
                              <div class="step_relational wizard_vertical" style="z-index: <?= ( count( $current_structures ) - $_structure['turn'] + 10 ) ?>;">
                                <ul class="wizard_steps">
<?php       foreach ( $current_structures as $_order => $_child_structure ) : 
              if ( $_child_structure['dependency'] == $_structure['id'] && $_child_structure['turn'] == 1 ) : 
                  $_child_step_order = $_order; ?>
                                  <li data-dependency="<?= esc_attr( $_child_structure['dependency'] ) ?>" data-structure-id="<?= esc_attr( $_child_structure['id'] ) ?>" data-group-id="<?= esc_attr( $_child_structure['group_id'] ) ?>" style="order: <?= esc_attr( $_order ) ?>">
                                    <a href="#" class="sub_storyline"><?= esc_html( $_child_structure['name'] ) ?></a>
                                  </li>
<?php         endif;
            endforeach; ?>
                                  <li data-parent-structure-id="<?= esc_attr( $_structure['id'] ) ?>" style="order: <?= esc_attr( $_order + 1 ) ?>">
                                    <a href="#" class="add_sub"><?php _e('Add Sub Storyline', WPGENT_DOMAIN ); ?></a>
                                  </li>
                                </ul>
                              </div>
                            </li>
<?php   } elseif ( $current_group_id == -1 ) { 
          $_step_order = 1;
          $_first_view_structure = [
            'id' => null,
            'dependency' => $current_dependency,
            'name' => sprintf( __( 'Sub Storyline Act %d', WPGENT_DOMAIN ), $_step_order ),
            'type' => 0,
            'group_id' => $current_group_id,
            'turn' => 1,
            'last_modified_by' => $current_user_id,
            'updated_at' => null,
            'context' => $first_view_context
          ]; ?>
                            <li data-structure-id="" data-group-id="<?= esc_attr( $current_group_id ) ?>" data-step="<?= esc_attr( $_step_order ) ?>" style="order: <?= esc_attr( $_step_order ) ?>">
                              <div class="step_indicator selected">
                                <a href="#act-form" class="step_no">1</a>
                                <ul class="step_meta">
                                  <li class="step_name"><?php printf( __( 'Sub Storyline Act %d', WPGENT_DOMAIN ), $_step_order ); ?></li>
                                </ul>
                                <button type="button" class="btn btn-round btn-default btn-sm btn-remove-act hide" title="<?php _e('Remove Act', WPGENT_DOMAIN ); ?>"><i class="plt-cross2"></i></button>
                              </div>
                              <div class="step_relational wizard_vertical" style="z-index: 10;">
<?php /*
                                <ul class="wizard_steps">
                                  <li data-parent-structure-id="" style="">
                                    <a href="#" class="add_sub"><?php _e('Add Sub Storyline', WPGENT_DOMAIN ); ?></a>
                                  </li>
                                </ul>
*/ ?>
                              </div>
                            </li>
<?php     break;
        };
      endforeach; ?>
                            <li data-step="last" style="order: <?= esc_attr( $_step_order * 10 ) ?>">
                              <div class="step_indicator add_new">
                                <a href="#act-new" class="step_no"><i class="plt-plus3"></i></a>
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
                            <input type="hidden" id="act-structure-id" name="structure_id" value="<?= esc_attr( $_first_view_structure['id'] ) ?>">
                            <input type="hidden" id="act-group-id" name="group_id" value="<?= esc_attr( $_first_view_structure['group_id'] ) ?>">
                            <input type="hidden" id="act-turn" name="turn" value="<?= esc_attr( $_first_view_structure['turn'] ) ?>">
                            <input type="hidden" id="act-diff" name="diff" value="false">
                            <input type="hidden" id="act-hash" name="hash" value="<?php if ( ! empty( $_first_view_structure['id'] ) ) { echo esc_attr( md5( $_first_view_structure['id'] ) ); } ?>">
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-name"><?php _e('Act Name', WPGENT_DOMAIN ); ?> <span class="required"></span>
                              </label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="act-name" name="name" required="required" class="form-control col-md-7 col-xs-12" placeholder="<?php _e('Act Name', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $_first_view_structure['name'] ) ?>">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-turn"><?php _e('Previous Act', WPGENT_DOMAIN ); ?> <span class="required"></span>
                              </label>
                              <div class="col-md-6 col-sm-9 col-xs-12">
                                <select id="change-turn" name="turn" required="required" class="form-control col-md-7 col-xs-12">
                                  <option value="0" <?php selected( 1, $_first_view_structure['turn'] ); ?>><?php _e( 'None, the first act on dependency', WPGENT_DOMAIN ) ?></option>
<?php foreach ( $current_structures as $_structure ) : 
          if ( $_structure['dependency'] == $current_dependency && $_structure['group_id'] == $current_group_id ) : ?>
                                  <option value="<?= esc_attr( $_structure['turn'] ) ?>"><?= esc_html( $_structure['name'] ) ?><?php if ( WP_DEBUG ) : 
              printf( " (%d)", esc_html( $_structure['turn'] ) );
              endif; ?></option>
<?php     endif;
      endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="act-context"><?php _e('Context', WPGENT_DOMAIN ); ?></label>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea id="act-context" name="context" class="form-control col-md-7 col-xs-12" rows="8" placeholder="<?php _e('Explanation of this act etc.', WPGENT_DOMAIN ); ?>"><?= esc_html( $_first_view_structure['context'] ) ?></textarea>
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
                          <li data-structure-id="%s" data-group-id="<?= $current_group_id ?>" data-step="%d" style="order: %d">
                            <div class="step_indicator">
                              <a href="javascript:;" class="step_no">%d</a>
                              <ul class="step_meta">
                                <li class="step_name"><?php _e('Act', WPGENT_DOMAIN ); ?> %d</li>
                              </ul>
                              <button type="button" class="btn btn-round btn-default btn-sm btn-remove-act hide" title="<?php _e('Remove Act', WPGENT_DOMAIN ); ?>" data-target-id="%N">
                                <i class="plt-cross2"></i>
                              </button>
                            </div>
                            <div class="step_relational wizard_vertical"></div>
                          </li>
                        </ul>
                        <ul class="last-step-template">
                          <li data-step="last" style="order: %d">
                            <div class="step_indicator add_new">
                              <a href="javascript:;" class="step_no"><i class="plt-plus3"></i></a>
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
<?php $buttons = [
        'cancel' => [ 'label' => __( 'Cancel', WPGENT_DOMAIN ), 'type' => 'default', 'addClass' => [],            'defaultView' => true  ],
        'remove' => [ 'label' => __( 'Remove', WPGENT_DOMAIN ), 'type' => 'dark',    'addClass' => [],            'defaultView' => $current_group_id == -1 ? false : true  ],
        'add'    => [ 'label' => __( 'Add',    WPGENT_DOMAIN ), 'type' => 'primary', 'addClass' => [ 'onValid' ], 'defaultView' => $current_group_id == -1 ? true : false ],
        'update' => [ 'label' => __( 'Commit', WPGENT_DOMAIN ), 'type' => 'primary', 'addClass' => [ 'onValid' ], 'defaultView' => $current_group_id == -1 ? false : true ],
      ];
      foreach ( $buttons as $_key => $_btn ) : 
        $_classes = 'btn btn-'. $_btn['type'];
        if ( ! empty( $_btn['addClass'] ) ) {
          $_classes .= ' '. implode( ' ', $_btn['addClass'] );
        }
        if ( ! $_btn['defaultView'] ) {
          $_classes .= ' hide';
        }
      ?>
                          <button class="<?= esc_attr( $_classes ) ?>" type="button" id="<?= esc_attr( $page_name ) ?>-btn-<?= esc_attr( $_key ) ?>"><?= esc_html( $_btn['label'] ) ?></button>
<?php endforeach; ?>
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

