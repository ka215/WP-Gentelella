<?php
/**
 * Template part for displaying edit-plot content in page.php
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
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
            <div class="page-title">
              <div class="title_left">
                <h3><?php _e( 'Storyline', WPGENT_DOMAIN ) ?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $current_source_name ?></h2>
                    <?php get_template_part( 'partials/toolbox' ); ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="plotSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                      <input type="hidden" name="from_page" value="<?= $page_name ?>">
                      <input type="hidden" name="source_id" value="<?= $current_source_id ?>">
                      <input type="hidden" name="post_action" id="global-post-action" value="">
                      <?php wp_nonce_field( 'global-setting_' . $current_user_id ); ?>
<?php if ( count( $user_sources ) > 0 ) : ?>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="change_source"><?php _e( 'Switch or Add Story', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" id="change_source" name="change_source">
                            <option value=""><?php _e( 'Add New Story', WPGENT_DOMAIN ); ?></option>
                          <?php foreach ( $user_sources as $_src ) : ?>
                            <option value="<?= $_src->id ?>" <?php selected( $_src->id, $current_source_id ); ?>><?= $_src->name ?><?php if ( $_src->id == $current_source_id ) echo ' - '. __( 'Currently', WPGENT_DOMAIN ) .' -'; ?></option>
                          <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
<?php endif; ?>
                      <h5><?php _e( 'Advanced Settings of Your Story', WPGENT_DOMAIN ); ?></h5>
                      <div class="ln_solid"></div>
                      <!-- p class="font-gray-dark">helper text</p -->
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="source_name"><?php _e( 'Title Of Story', WPGENT_DOMAIN ); ?> <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="source_name" name="source_name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'Your Story Title', WPGENT_DOMAIN ); ?>" value="<?= $current_source_name ?>" required="required">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="who"><?php _e( 'Who?', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                          <input type="text" id="who" name="who" class="form-control col-md-7 col-xs-12 optional" data-validate-length-range="0,100" placeholder="<?php _e( 'Whose story is this?', WPGENT_DOMAIN ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="what"><?php _e( 'What?', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                          <input type="text" id="what" name="what" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'What will that one do with this story?', WPGENT_DOMAIN ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="where"><?php _e( 'Where?', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                          <input type="text" id="where" name="where" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'Where is the world of this story?', WPGENT_DOMAIN ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="when"><?php _e( 'When?', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                          <input type="text" id="when" name="when" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'When is this story?', WPGENT_DOMAIN ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="why"><?php _e( 'Why?', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                          <input type="text" id="why" name="why" class="form-control col-md-7 col-xs-12" data-validate-length-range="0,100" placeholder="<?php _e( 'Why do that one do it?', WPGENT_DOMAIN ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="team_writing"><?php _e( 'Enable team writing', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="">
                            <label>
                              <input type="checkbox" id="team_writing" name="team_writing" class="js-switch" />
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="permission"><?php _e( 'Permission', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <select class="form-control" id="permission" name="permission" readonly="readonly" disabled="disabled">
                            <option value="owner" <?php selected( 'owner', 'owner' ); ?>><?php _e( 'Owner', WPGENT_DOMAIN ); ?></option>
                            <option value="editor" <?php selected( 'owner', 'editor' ); ?>><?php _e( 'Editor', WPGENT_DOMAIN ); ?></option>
                            <option value="director" <?php selected( 'owner', 'director' ); ?>><?php _e( 'Director', WPGENT_DOMAIN ); ?></option>
                            <option value="writer" <?php selected( 'owner', 'writer' ); ?>><?php _e( 'Writer', WPGENT_DOMAIN ); ?></option>
                            <option value="reader" <?php selected( 'owner', 'reader' ); ?>><?php _e( 'Reader', WPGENT_DOMAIN ); ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                          <button class="btn btn-default" type="button" id="global-btn-cancel"><?php _e( 'Cancel', WPGENT_DOMAIN ); ?></button>
<?php if ( count( $user_sources ) > 0 ) : ?>
                          <button class="btn btn-primary" type="button" id="global-btn-remove"><?php _e( 'Remove', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-success onValid" type="button" id="global-btn-update"><?php _e( 'Update', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-success onValid hide" type="button" id="global-btn-add"   ><?php _e( 'Add',    WPGENT_DOMAIN ); ?></button>
<?php endif; ?>
                        </div>
                      </div>

                    </form>

<?php /*
  wp_link_pages( array(
    'before' => '<div class="page-links">' . __( 'Pages:', WPGENT_DOMAIN ),
    'after'  => '</div>',
  ) );
*/ ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.right_col -->

