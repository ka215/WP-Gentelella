<?php
/**
 * The sidebar template file
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
if ( ! is_active_sidebar( 'side-menu' ) ) {
  return;
} else {
  $_plotter               = get_query_var( 'plotter', [] );
  $now_page               = @$_plotter['page_name'] ?: '';
  $current_user_id        = @$_plotter['current_user_id'] ?: null;
  $in_team_work           = @$_plotter['in_team_work'] ?: false;
  $user_sources           = @$_plotter['user_sources'] ?: [];
  $current_source_id      = @$_plotter['current_source_id'] ?: null;
  $current_source_name    = @$_plotter['current_source_name'] ?: '';
  $has_current_structures = @$_plotter['has_current_structures'] ?: false;
  $user_approval_state    = @$_plotter['approval_state'] ?: false;
}
?>

        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="/dashboard/" class="site_title"><i class="plt-quill3"></i> <span><?= __( 'Plotter', WPGENT_DOMAIN ) ?></span></a>
            </div>

<?php if ( SIDEBAR_SEARCH && ! empty( $user_sources ) ) : ?>
            <div class="clearfix"></div>

            <div class="main_menu_side hidden-print hidden-small">
              <div id="search-section" class="menu_section">
                <div class="form-group top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="<?= __( 'Search for...', WPGENT_DOMAIN ) ?>">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="plt-search3"></i></button>
                    </span>
                  </div>
                </div>
              </div>
              <!-- #search-section -->
            </div>
<?php endif; ?>
            <div class="clearfix"></div>

            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div id="section-1" class="menu_section">
<?php if ( $user_approval_state ) : ?>
                <h3><?= __( 'Structures', WPGENT_DOMAIN ) ?></h3>
<?php endif; ?>
                <ul class="nav side-menu">
<?php if ( $user_approval_state ) : ?>
                  <li><a href="/whole-story/">
                    <i class="plt-earth3"></i> <?= __( 'Whole Story', WPGENT_DOMAIN ) ?>
                  </a></li>
<?php endif;
      if ( ! empty( $user_sources ) ) : ?>
                  <li><a href="/<?php if ( $has_current_structures ) : ?>edit-storyline<?php else : ?>create-new<?php endif; ?>/">
                    <i class="plt-tree7"></i> <?php _e('Storyline', WPGENT_DOMAIN) ?>
                  </a></li>
                  <li><a href="#">
                    <i class="plt-puzzle4"></i> <?php _e('Scene', WPGENT_DOMAIN) ?> <span class="plt-circle-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( isset( $_plotter['current_sequences'] ) ) : ?>
                      <li><a href="/edit-sequence/"><?php _e('All Sequences', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-sequence/"><?php _e('Add Sequence', WPGENT_DOMAIN) ?></a></li>
<?php     if ( isset( $_plotter['current_scenes'] ) ) : ?>
                      <li><a href="/edit-scene/"><?php _e('All Scenes', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-scene/"><?php _e('Add Scene', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
<?php endif; ?>
                </ul>
              </div>
<?php if ( ! empty( $user_sources ) ) : ?>
              <!-- /#section-1 -->
              <div id="section-2" class="menu_section">
                <h3><?php _e('Journals', WPGENT_DOMAIN) ?></h3>
                <ul class="nav side-menu">
                  <li><a><i class="plt-stack-user"></i> <?php _e('Characters', WPGENT_DOMAIN) ?> <span class="plt-circle-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( isset( $_plotter['current_journals'] ) ) : ?>
                      <li><a href="/edit-char/"><?php _e('Character List', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-char/"><?php _e('Add Character', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="/timeline/"><?php _e('Timelines', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="plt-stack-text"></i> <?php _e('Terms', WPGENT_DOMAIN) ?> <span class="plt-circle-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( isset( $_plotter['current_journals'] ) ) : ?>
                      <li><a href="/edit-term/"><?php _e('Term List', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-term/"><?php _e('Add Term', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="plt-stack-star"></i> <?php _e('Objects', WPGENT_DOMAIN) ?> <span class="plt-circle-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( isset( $_plotter['current_journals'] ) ) : ?>
                      <li><a href="/edit-object/"><?php _e('Object List', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-object/"><?php _e('Add Object', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="plt-stack-picture"></i> <?php _e('Image Gallery', WPGENT_DOMAIN) ?> <span class="plt-circle-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( isset( $_plotter['current_journals'] ) ) : ?>
                      <li><a href="/edit-image/"><?php _e('Image List', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-image/"><?php _e('Add Image', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <!-- /#section-2 -->
              <div id="section-3" class="menu_section">
                <h3><?php _e('Utility', WPGENT_DOMAIN) ?></h3>
                <ul class="nav side-menu">
                  <li><a href="/idea-note/"><i class="plt-lamp8"></i> <?php _e('Ideas', WPGENT_DOMAIN) ?></a></li>
<?php /* No release yet to under development
                  <li><a><i class="plt-book"></i> <?php _e('Treatments', WPGENT_DOMAIN) ?> <span class="plt-circle-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/edit-treat/"><?php _e('All Treatments', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="/add-treat/"><?php _e('Add Treatment', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
*/ ?>
<?php     if ( $in_team_work ) : ?>
                  <li><a href="/collaboration/"><i class="plt-collaboration"></i> <?php _e('Collaboration', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
<?php /* No release yet to under development
                  <li><a><i class="plt-archive"></i> <?php _e('Extensions', WPGENT_DOMAIN) ?> <span class="plt-circle-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/export/"><?php _e('Export', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="/import/"><?php _e('Import', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
*/ ?>
<?php /* menu for extended
                  <li><a href="javascript:void(0)"><i class="fa fa-heart-o"></i> <?php _e('New Function', WPGENT_DOMAIN) ?> <span class="label label-success pull-right"><?php _e('Coming Soon', WPGENT_DOMAIN) ?></span></a></li>
*/ ?>
                </ul>
              </div>
              <!-- /#section-2 -->
<?php endif; ?>
            </div>
            <!-- /#sidebar-menu -->

            <div id="menu-footer-buttons" class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="<?= __( 'Settings', WPGENT_DOMAIN ) ?>" href="/settings/" name="settings">
                <i class="plt-cog3"></i>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?= __( 'FullScreen', WPGENT_DOMAIN ) ?>" name="fullscreen">
                <i class="plt-enlarge2"></i>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?= __( 'Help', WPGENT_DOMAIN ) ?>" href="/help/" name="help">
                <i class="plt-question3"></i>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?= __( 'Sign Out', WPGENT_DOMAIN ) ?>" href="<?= wp_logout_url(); ?>" name="signout">
                <i class="plt-exit2"></i>
              </a>
            </div>
            <!-- /#menu-footer-buttons -->
          </div>
          <!-- /.left_col.scroll-view -->
        </div>
        <!-- /.col-md-3.left_col -->
