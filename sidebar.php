<?php

if ( ! is_active_sidebar( 'side-menu' ) ) {
  return;
} else {
  $_plotter = get_query_var( 'plotter' );
  // $_datamodel = __ctl( 'model' );
}
?>

        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="/dashboard/" class="site_title"><i class="plt-quill3"></i> <span><?php _e('Plotter', WPGENT_DOMAIN) ?></span></a>
            </div>

<?php if ( ! empty( $_plotter['user_sources'] ) ) : ?>
            <div class="clearfix"></div>

            <div class="main_menu_side hidden-print hidden-small">
              <div id="search-section" class="menu_section">
                <div class="form-group top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="<?php _e('Search for...', WPGENT_DOMAIN) ?>">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
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
                <h3><?php _e('Structures', WPGENT_DOMAIN) ?></h3>
                <ul class="nav side-menu">
                  <li><a href="/global/"><i class="fa fa-globe"></i> <?php _e('Whole Story', WPGENT_DOMAIN) ?></a></li>
<?php if ( ! empty( $_plotter['user_sources'] ) ) : ?>
                  <li><a><i class="fa fa-pencil"></i> <?php _e('Storyline', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( ! empty( $_plotter['current_structures'] ) ) : ?>
                      <li><a href="/edit-storyline/"><?php _e('Edit Storyline', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/create-new/"><?php _e('Create New', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-sitemap"></i> <?php _e('Scene / Sequence', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( ! empty( $_plotter['current_sequences'] ) ) : ?>
                      <li><a href="/edit-sequence/"><?php _e('All Sequences', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-sequence/"><?php _e('Add Sequence', WPGENT_DOMAIN) ?></a></li>
<?php     if ( ! empty( $_plotter['current_scenes'] ) ) : ?>
                      <li><a href="/edit-scene/"><?php _e('All Scenes', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-scene/"><?php _e('Add Scene', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
<?php endif; ?>
                </ul>
              </div>
<?php if ( ! empty( $_plotter['user_sources'] ) ) : ?>
              <!-- /#section-1 -->
              <div id="section-2" class="menu_section">
                <h3><?php _e('Journals', WPGENT_DOMAIN) ?></h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-id-card-o"></i> <?php _e('Characters', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( ! empty( $_plotter['current_journals'] ) ) : ?>
                      <li><a href="/edit-char/"><?php _e('Character List', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-char/"><?php _e('Add Character', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="/timeline/"><?php _e('Timelines', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-tags"></i> <?php _e('Terms', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( ! empty( $_plotter['current_journals'] ) ) : ?>
                      <li><a href="/edit-term/"><?php _e('Term List', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-term/"><?php _e('Add Term', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-puzzle-piece"></i> <?php _e('Objects', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( ! empty( $_plotter['current_journals'] ) ) : ?>
                      <li><a href="/edit-object/"><?php _e('Object List', WPGENT_DOMAIN) ?></a></li>
<?php     endif; ?>
                      <li><a href="/add-object/"><?php _e('Add Object', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-image"></i> <?php _e('Image Gallery', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
<?php     if ( ! empty( $_plotter['current_journals'] ) ) : ?>
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
                  <li><a><i class="fa fa-lightbulb-o"></i> <?php _e('Ideas', WPGENT_DOMAIN) ?></a></li>
                  <li><a><i class="fa fa-archive"></i> <?php _e('Treatments', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/edit-treat/"><?php _e('All Treatments', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="/add-treat/"><?php _e('Add Treatment', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-exchange"></i> <?php _e('Extensions', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/export/"><?php _e('Export', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="/import/"><?php _e('Import', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
<?php /*
                  <li><a href="javascript:void(0)"><i class="fa fa-heart-o"></i> <?php _e('New Function', WPGENT_DOMAIN) ?> <span class="label label-success pull-right"><?php _e('Coming Soon', WPGENT_DOMAIN) ?></span></a></li>
*/ ?>
                </ul>
              </div>
              <!-- /#section-2 -->
<?php endif; ?>
            </div>
            <!-- /#sidebar-menu -->

            <div id="menu-footer-buttons" class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="<?php _e('Settings', WPGENT_DOMAIN); ?>">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?php _e('FullScreen', WPGENT_DOMAIN); ?>">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?php _e('Lock', WPGENT_DOMAIN); ?>">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?php _e('Sign Out', WPGENT_DOMAIN); ?>" href="<?= wp_logout_url(); ?>">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /#menu-footer-buttons -->
          </div>
          <!-- /.left_col.scroll-view -->
        </div>
        <!-- /.col-md-3.left_col -->
