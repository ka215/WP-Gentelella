<?php

if ( ! is_active_sidebar( 'side-menu' ) ) {
  return;
}
?>

        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="/dashboard/" class="site_title"><i class="plt-quill3"></i> <span><?php _e('Plotter', WPGENT_DOMAIN) ?></span></a>
            </div>

<?php if ( __ctl( 'model' )->have_sources() ) : ?>
            <div class="clearfix"></div>

            <div class="main_menu_side hidden-print hidden-small">
              <div id="search-section" class="menu_section">
                <div class="form-group top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
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
                  <li><a href="/global/"><i class="fa fa-globe"></i> <?php _e('Global Settings', WPGENT_DOMAIN) ?></a></li>
<?php if ( __ctl( 'model' )->have_sources() ) : ?>
                  <li><a><i class="fa fa-pencil"></i> <?php _e('Whole Story', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Main Plot', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Sub Plot 1', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Sub Plot 2', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Sub Plot 3', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-sitemap"></i> <?php _e('Scene / Sequence', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('All Sequences', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Add Sequence', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('All Scenes', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Add Scene', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
<?php endif; ?>
                </ul>
              </div>
<?php if ( __ctl( 'model' )->have_sources() ) : ?>
              <!-- /#section-1 -->
              <div id="section-2" class="menu_section">
                <h3><?php _e('Journals', WPGENT_DOMAIN) ?></h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-id-card-o"></i> <?php _e('Characters', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Character List', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Add Character', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Timelines', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-tags"></i> <?php _e('Terms', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Term List', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Add Term', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-puzzle-piece"></i> <?php _e('Objects', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Object List', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Add Object', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-image"></i> <?php _e('Image Gallery', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Image List', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Add Images', WPGENT_DOMAIN) ?></a></li>
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
                      <li><a href="#"><?php _e('All Treatments', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="#"><?php _e('Add Treatment', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-exchange"></i> <?php _e('Extensions', WPGENT_DOMAIN) ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="page_403.html"><?php _e('Export', WPGENT_DOMAIN) ?></a></li>
                      <li><a href="page_404.html"><?php _e('Import', WPGENT_DOMAIN) ?></a></li>
                    </ul>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-heart-o"></i> <?php _e('New Function', WPGENT_DOMAIN) ?> <span class="label label-success pull-right"><?php _e('Coming Soon', WPGENT_DOMAIN) ?></span></a></li>
                </ul>
              </div>
              <!-- /#section-2 -->
<?php endif; ?>
            </div>
            <!-- /#sidebar-menu -->

            <div id="menu-footer-buttons" class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /#menu-footer-buttons -->
          </div>
          <!-- /.left_col.scroll-view -->
        </div>
        <!-- /.col-md-3.left_col -->
