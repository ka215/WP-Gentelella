<?php
/**
 * Template part for displaying global-settings content in page.php
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */
$current_user_id     = get_current_user_id();
$user_sources        = plt_ctl()->get_sources( $current_user_id, 'row' );
$current_source_id   = plt_ctl('libs')->current_source();
foreach ( $user_sources as $_obj ) {
    if ( (int) $_obj->id == (int) $current_source_id ) {
        $current_source_name = $_obj->name;
        break;
    }
}
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
            <div class="page-title">
              <div class="title_left">
                <h3><?php if ( is_first_visit() ) {
                    _e( 'Welcome Plotter!', 'wpgentelella' );
                } else {
                    echo empty( $user_sources ) ? _e( "Let's add a new story", 'wpgentelella' ) : $current_source_name;
                } ?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php if ( is_first_visit() || empty( $user_sources ) ) {
                        _e( "First of all, let's enter the title of your story.", 'wpgentelella' );
                    } else {
                        _e( "Let's get started!", 'wpgentelella' );
                    } ?></h2>
                    <?php get_template_part( 'partials/toolbox' ); ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<?php if ( is_first_visit() || empty( $user_sources ) ) : ?>
                    <form id="initialSettings" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                      <input type="hidden" name="from_page" value="<?php echo get_pageinfo( 'page_name' ); ?>">
                      <?php wp_nonce_field( 'initial-setting_' . get_current_user_id() ); ?>
                      <p><?php _e( 'Even an unsettled title is fine. This title of the story can be edited after registering.', 'wpgentelella' ); ?></p>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_name"><?php _e( 'Title Of Story', 'wpgentelella' ); ?> <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="source_name" name="source_name" class="form-control" placeholder="<?php _e( 'Your Story Title', 'wpgentelella' ); ?>" required="required">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <button type="submit" class="btn btn-success"><?php _e( 'Register', 'wpgentelella' ); ?></button>
                        </div>
                      </div>
                    </form>
<?php else : ?>

                    <form id="globalSettings" data-parsley-validate class="form-horizontal form-label-left" method="post" novalidate>
                      <input type="hidden" name="from_page" value="<?= get_pageinfo( 'page_name' ) ?>">
                      <input type="hidden" name="source_id" value="<?= $current_source_id ?>">
                      <input type="hidden" name="post_action" id="global-post-action" value="">
                      <?php wp_nonce_field( 'global-setting_' . get_current_user_id() ); ?>
<?php if ( count( $user_sources ) > 0 ) : ?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="change_source"><?php _e( 'Switch stories or Add new', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" id="change_source" name="change_source">
                            <option value=""><?php _e( 'Add New Story', 'wpgentelella' ); ?></option>
                          <?php foreach ( $user_sources as $_src ) : ?>
                            <option value="<?= $_src->id ?>" <?php selected( $_src->id, $current_source_id ); ?>><?= $_src->name ?></option>
                          <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
<?php endif; ?>
                      <h5><?php _e( 'Advanced Settings of Your Story', 'wpgentelella' ); ?></h5>
                      <div class="ln_solid"></div>
                      <!-- p class="font-gray-dark">helper text</p -->
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_name"><?php _e( 'Title Of Story', 'wpgentelella' ); ?> <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="source_name" name="source_name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'Your Story Title', 'wpgentelella' ); ?>" value="<?= $current_source_name ?>" required="required">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="who"><?php _e( 'Who?', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="who" name="who" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'Whose story is this?', 'wpgentelella' ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="what"><?php _e( 'What?', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="what" name="what" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'What will that one do with this story?', 'wpgentelella' ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="where"><?php _e( 'Where?', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="where" name="where" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'Where is the world of the story?', 'wpgentelella' ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="when"><?php _e( 'When?', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="when" name="when" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'When is that story?', 'wpgentelella' ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="why"><?php _e( 'Why?', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="why" name="why" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'Why will do that one do that?', 'wpgentelella' ); ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="team-writing"><?php _e( 'Enable team writing', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="">
                            <label>
                              <input type="checkbox" id="team-writing" name="team_writing" class="js-switch" />
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="permission"><?php _e( 'Permission', 'wpgentelella' ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" id="permission" name="permission"<?php if ( count( $user_sources ) < 2 ) : ?> readonly="readonly"<?php endif; ?>>
                            <option value="owner"><?php _e( 'Owner', 'wpgentelella' ); ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button class="btn btn-default" type="button" id="global-btn-cancel"><?php _e( 'Cancel', 'wpgentelella' ); ?></button>
<?php if ( count( $user_sources ) > 0 ) : ?>
                          <button class="btn btn-primary" type="button" id="global-btn-remove"><?php _e( 'Remove', 'wpgentelella' ); ?></button>
                          <button class="btn btn-success" type="button" id="global-btn-update"><?php _e( 'Update', 'wpgentelella' ); ?></button>
                          <button class="btn btn-success hide" type="button" id="global-btn-add"   ><?php _e( 'Add',    'wpgentelella' ); ?></button>
<?php endif; ?>
                        </div>
                      </div>

                    </form>

<?php /*
  wp_link_pages( array(
    'before' => '<div class="page-links">' . __( 'Pages:', 'wpgentelella' ),
    'after'  => '</div>',
  ) );
*/ endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.right_col -->

