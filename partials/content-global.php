<?php
/**
 * Template part for displaying global-settings content in page.php
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */

$user_sources = plt_ctl()->get_sources( get_current_user_id() );
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class(); ?>>
            <div class="page-title">
              <div class="title_left">
                <h3><?php if ( is_first_visit() ) {
                    _e( 'Welcome Plotter!', 'wpgentelella' );
                } else {
                    echo empty( $user_sources ) ? _e( "Let's add a new story", 'wpgentelella' ) : $user_sources[0];
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
                      <input type="hidden" name="from_page" value="<?php echo get_pageinfo( 'page_name' ); ?>">
                      <input type="hidden" name="source_id" value="">
                      <?php wp_nonce_field( 'global-setting_' . get_current_user_id() ); ?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="source_name"><?php _e( 'Title Of Story', 'wpgentelella' ); ?> <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="source_name" name="source_name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'Your Story Title', 'wpgentelella' ); ?>" required="required">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="who">Who?</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="who" name="who" class="form-control col-md-7 col-xs-12" placeholder="Whose story is this?">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="what">What?</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="what" name="what" class="form-control col-md-7 col-xs-12" placeholder="What will that one do with this story?">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Where? </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="where" name="where" class="form-control col-md-7 col-xs-12" placeholder="Where is the world of the story?">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">When? </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="when" name="when" class="form-control col-md-7 col-xs-12" placeholder="When is that story?">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Why? </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="why" name="why" class="form-control col-md-7 col-xs-12" placeholder="Why will do that one do that?">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button class="btn btn-primary" type="button">Cancel</button>
                          <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Submit</button>
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

