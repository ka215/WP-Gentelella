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
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="change_source"><?php _e( 'Storyline Type', WPGENT_DOMAIN ); ?></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" id="structure-presets" name="structure_type">
                            <option value="custom"><?php _e( 'Custom Structure', WPGENT_DOMAIN ); ?></option>
                            <option value="3acts"><?php _e( 'Three-act Structure', WPGENT_DOMAIN ); ?></option>
                            <option value="4acts"><?php _e( 'Four-act Structure', WPGENT_DOMAIN ); ?></option>
                          </select>
                        </div>
                      </div>
<?php endif; ?>
                      <h5><?php _e( 'Advanced Settings of Storyline', WPGENT_DOMAIN ); ?></h5>
                      <div class="ln_solid"></div>
                      <!-- p class="font-gray-dark">helper text</p -->

<?php /* Smart Wizard */ ?>
                      <p>This is a basic form wizard example that inherits the colors from the selected scheme.</p>
                      <div id="wizard" class="form_wizard wizard_horizontal">
                        <ul class="wizard_steps">
                          <li><a href="#act-1">
                            <span class="step_no">1</span>
                            <span class="step_descr">Act 1<br /><small>Set-up</small></span>
                          </a></li>
                          <li><a href="#act-2">
                            <span class="step_no">2</span>
                            <span class="step_descr">Act 2<br /><small>Confrontation</small></span>
                          </a></li>
                          <li><a href="#act-3">
                            <span class="step_no">3</span>
                            <span class="step_descr">Act 3<br /><small>Resolution</small></span>
                          </a></li>
                          <li><a href="#act-4">
                            <span class="step_no">4</span>
                            <span class="step_descr">Act 4<br /><small>Afterwards</small></span>
                          </a></li>
                      </ul>

                      <div id="act-1">
                        <form class="form-horizontal form-label-left">

                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">First Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Last Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="last-name" name="last-name" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Middle Name / Initial</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="middle-name">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <div id="gender" class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                  <input type="radio" name="gender" value="male"> &nbsp; Male &nbsp;
                                </label>
                                <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                  <input type="radio" name="gender" value="female"> Female
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input id="birthday" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text">
                            </div>
                          </div>

                        </form>

                      </div>
                      <div id="act-2">
                        <h2 class="StepTitle">Step 2 Content</h2>
                        <p>
                          do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                          fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                      </div>
                      <div id="act-3">
                        <h2 class="StepTitle">Step 3 Content</h2>
                        <p>
                          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
                          eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                      </div>
                      <div id="act-4">
                        <h2 class="StepTitle">Step 4 Content</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                          Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                      </div>

                    </div>
                    <!-- End SmartWizard Content -->


                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="act1_name"><?php _e( 'Act Name', WPGENT_DOMAIN ); ?> <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="act1_name" name="act1_name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'ACT ONE: as Set-up', WPGENT_DOMAIN ); ?>" value="<?= isset( $current_act1_name ) ? esc_attr( $current_act1_name ) : '' ?>" required="required">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="act2_name"><?php _e( 'Act Name', WPGENT_DOMAIN ); ?> <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="act2_name" name="act2_name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'ACT TWO: as Confrontation', WPGENT_DOMAIN ); ?>" value="<?= isset( $current_act2_name ) ? esc_attr( $current_act2_name ) : '' ?>" required="required">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="act3_name"><?php _e( 'Act Name', WPGENT_DOMAIN ); ?> <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="act3_name" name="act3_name" class="form-control col-md-7 col-xs-12" placeholder="<?php _e( 'ACT THREE: as Resolution', WPGENT_DOMAIN ); ?>" value="<?= isset( $current_act3_name ) ? esc_attr( $current_act3_name ) : '' ?>" required="required">
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
                          <button class="btn btn-default" type="button" id="<?= esc_attr( $page_name ) ?>-btn-cancel"><?php _e( 'Cancel', WPGENT_DOMAIN ); ?></button>
<?php if ( count( $user_sources ) > 0 ) : ?>
                          <button class="btn btn-primary" type="button" id="<?= esc_attr( $page_name ) ?>-btn-remove"><?php _e( 'Remove', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-success onValid" type="button" id="<?= esc_attr( $page_name ) ?>-btn-update"><?php _e( 'Update', WPGENT_DOMAIN ); ?></button>
                          <button class="btn btn-success onValid hide" type="button" id="<?= esc_attr( $page_name ) ?>-btn-add"   ><?php _e( 'Add',    WPGENT_DOMAIN ); ?></button>
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

