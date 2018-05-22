<?php
/**
 * Template part for displaying whole-story content in page.php
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
$current_source_data = @$_plotter['current_source_data'] ?: [];
$current_user_role   = @$_plotter['current_user_role'] ?: 'undefined';
$display_role = [
  'owner'     => __( 'Owner', WPGENT_DOMAIN ), 
  'member'    => __( 'Member', WPGENT_DOMAIN ), 
  'undefined' => ''
];
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class( 'flex-container' ); ?>>
<?php /* if ( empty( $user_sources ) ) : ?>
            <div class="page-title">
              <div class="title_left">
                <h2><?= __( "Let's weave a new story!", WPGENT_DOMAIN ) ?></h2>
              </div>
            </div><!-- /.page-title -->
<?php endif; */ /*
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"> */ ?>
            <div class="x_panel panel-primary">
              <div class="x_title">
                <h3><?php if ( empty( $user_sources ) ) : 
                ?><i class="plt-quill3 blue"></i> <?= __( "Let's weave a new story!", WPGENT_DOMAIN ) ?><?php 
                else : 
                ?><i class="plt-earth3 blue"></i> <?= __( "Whole Story", WPGENT_DOMAIN ) ?><?php 
                endif; ?></h3>
                <?php get_template_part( 'partials/toolbox' ); ?>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form id="globalSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
<?php if ( empty( $user_sources ) ) : ?>
                  <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                  <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="regist">
                  <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>
                  <p><?= __( "First of all, let's enter the title of your story.", WPGENT_DOMAIN ) ?></p>
                  <p><?= __( 'Even an unsettled title is fine. This title of the story can be edited after registering.', WPGENT_DOMAIN ) ?></p>
                  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_name"><?= __( 'Title Of Story', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="source_name" name="source_name" class="form-control" placeholder="<?= __( 'Your Story Title', WPGENT_DOMAIN ) ?>" required="required">
                    </div>
                  </div>
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                      <button type="submit" class="btn btn-primary" id="<?= esc_attr( $page_name ) ?>-btn-regist"><?php _e( 'Register', WPGENT_DOMAIN ); ?></button>
                    </div>
                  </div>
<?php else : ?>
                  <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                  <input type="hidden" name="source_id" value="<?= esc_attr( $current_source_id ) ?>">
                  <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                  <?php wp_nonce_field( $page_name . '-setting_' . $current_user_id, '_token', true, true ); ?>
<?php if ( count( $user_sources ) > 0 ) : ?>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="change_source"><?php _e( 'Switch or Add Story', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control selectpicker" id="change_source" name="change_source" data-width="100%">
                        <option value=""><?php _e( 'Add New Story', WPGENT_DOMAIN ); ?></option>
                        <option data-divider="true"></option>
                      <?php foreach ( $user_sources as $_src ) : ?>
                        <option value="<?= esc_attr( $_src['id'] ) ?>" <?php selected( $_src['id'], $current_source_id ); ?><?php if ( $_src['id'] == $current_source_id ) : ?>
                        data-content="<?= esc_html( $_src['name'] ) ?> <i class='plt-book blue' aria-label='<?= __( 'Currently', WPGENT_DOMAIN ) ?>'></i>"<?php endif; ?>><?= esc_html( $_src['name'] ) ?></option>
                      <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
<?php endif; ?>
                  <div class="ln_solid"></div>
                  <h4><i class="plt-equalizer3 blue"></i> <?php _e( 'Advanced Settings of Your Story', WPGENT_DOMAIN ); ?></h4>
                  <div class="ln_dotted ln_thin"></div>
                  <!-- p class="font-gray-dark">helper text</p -->
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="source_name"><?php _e( 'Title Of Story', WPGENT_DOMAIN ); ?> <span class="required"></span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="source_name" name="source_name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'Your Story Title', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $current_source_name ) ?>" required="required">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="who"><?php _e( 'Who?', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                      <input type="text" id="who" name="who" class="form-control col-md-7 col-xs-12 optional" data-validate-length-range="0,100" placeholder="<?php _e( 'Whose story is this?', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $current_source_data['who'] ) ?>">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="what"><?php _e( 'What?', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                      <input type="text" id="what" name="what" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'What will that one do with this story?', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $current_source_data['what'] ) ?>">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="where"><?php _e( 'Where?', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                      <input type="text" id="where" name="where" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'Where is the world of this story?', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $current_source_data['where'] ) ?>">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="when"><?php _e( 'When?', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                      <input type="text" id="when" name="when" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'When is this story?', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $current_source_data['when'] ) ?>">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="why"><?php _e( 'Why?', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                      <input type="text" id="why" name="why" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'Why do that one do it?', WPGENT_DOMAIN ); ?>" value="<?= esc_attr( $current_source_data['why'] ) ?>">
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="team_writing"><?php _e( 'Enable team writing', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="">
                        <label>
                          <input type="checkbox" id="team_writing" name="team_writing" class="js-switch" <?php if ( $current_source_data['type'] == 1 ) : ?>checked="checked"<?php endif; ?> />
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="permission"><?php _e( 'Role', WPGENT_DOMAIN ); ?></label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <p class="form-control" readonly><?= $display_role[$current_user_role] ?></p>
<?php /*
                      <select class="form-control hide" id="permission-select" name="permission" readonly="readonly" disabled="disabled">
                        <option value="owner" <?php selected( $current_permission, 'owner' ); ?>><?php _e( 'Owner', WPGENT_DOMAIN ); ?></option>
                        <option value="editor" <?php selected( $current_permission, 'editor' ); ?>><?php _e( 'Editor', WPGENT_DOMAIN ); ?></option>
                        <option value="director" <?php selected( $current_permission, 'director' ); ?>><?php _e( 'Director', WPGENT_DOMAIN ); ?></option>
                        <option value="writer" <?php selected( $current_permission, 'writer' ); ?>><?php _e( 'Writer', WPGENT_DOMAIN ); ?></option>
                        <option value="reader" <?php selected( $current_permission, 'reader' ); ?>><?php _e( 'Reader', WPGENT_DOMAIN ); ?></option>
                      </select>
                      <input type="text" id="permission" name="permission" class="form-control col-md-6 col-xs-12 hide" readonly="readonly" disabled="disabled" value="<?php _e( 'Owner', WPGENT_DOMAIN ); ?>">
*/ ?>
                    </div>
                  </div>
                  <div class="item form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                      <p class="help-block"><?= __( 'Notes: if you want to save changes, you need to commit.', WPGENT_DOMAIN ) ?></p>
                    </div>
                  </div>
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                      <button class="btn btn-default" type="button" id="<?= esc_attr( $page_name ) ?>-btn-cancel"><?php _e( 'Cancel', WPGENT_DOMAIN ); ?></button>
<?php if ( count( $user_sources ) > 0 ) : ?>
                      <button class="btn btn-dark" type="button" id="<?= esc_attr( $page_name ) ?>-btn-remove-confirm"><?php _e( 'Remove', WPGENT_DOMAIN ); ?></button>
                      <button class="btn btn-primary onValid" type="button" id="<?= esc_attr( $page_name ) ?>-btn-update"><?php _e( 'Commit', WPGENT_DOMAIN ); ?></button>
                      <button class="btn btn-primary onValid hide" type="button" id="<?= esc_attr( $page_name ) ?>-btn-add"   ><?php _e( 'Add',    WPGENT_DOMAIN ); ?></button>
<?php endif; ?>
                    </div>
                  </div>
<?php endif; ?>
                </form>
              </div><!-- /.x_content -->
            </div><!-- /.x_panel -->
          </div><!-- /.flex-container -->
<?php /*
            </div><!-- /.col -->
          </div><!-- /.row --> */ ?>
        </div>
        <!-- /.right_col -->
