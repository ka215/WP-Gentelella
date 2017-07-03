<?php

if ( ! is_active_sidebar( 'side-menu' ) ) {
  return;
}
?>

        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="/dashboard/" class="site_title"><i class="plt-quill3"></i> <span><?php _e('Plotter', 'wpgentelella') ?></span></a>
            </div>

            <div class="clearfix"></div>

            <div class="main_menu_side hidden-print hidden-small">
              <div class="form-group top_search">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Search for...">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Go!</button>
                  </span>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3><?php _e('Structures', 'wpgentelella') ?></h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-globe"></i> <?php _e('Global Settings', 'wpgentelella') ?></a></li>
                  <li><a><i class="fa fa-pencil"></i> <?php _e('Whole Story', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Main Plot', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Sub Plot 1', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Sub Plot 2', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Sub Plot 3', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-sitemap"></i> <?php _e('Scene / Sequence', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('All Sequences', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Add Sequence', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('All Scenes', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Add Scene', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <h3><?php _e('Journals', 'wpgentelella') ?></h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-id-card-o"></i> <?php _e('Characters', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Character List', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Add Character', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Timelines', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-tags"></i> <?php _e('Terms', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Term List', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Add Term', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-puzzle-piece"></i> <?php _e('Objects', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Object List', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Add Object', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-image"></i> <?php _e('Image Gallery', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('Image List', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Add Images', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <h3><?php _e('Utility', 'wpgentelella') ?></h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-lightbulb-o"></i> <?php _e('Ideas', 'wpgentelella') ?></a></li>
                  <li><a><i class="fa fa-archive"></i> <?php _e('Treatments', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?php _e('All Treatments', 'wpgentelella') ?></a></li>
                      <li><a href="#"><?php _e('Add Treatment', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-exchange"></i> <?php _e('Extensions', 'wpgentelella') ?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="page_403.html"><?php _e('Export', 'wpgentelella') ?></a></li>
                      <li><a href="page_404.html"><?php _e('Import', 'wpgentelella') ?></a></li>
                    </ul>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-heart-o"></i> <?php _e('New Function', 'wpgentelella') ?> <span class="label label-success pull-right"><?php _e('Coming Soon', 'wpgentelella') ?></span></a></li>
                </ul>
              </div>

            </div>
            <!-- /#sidebar-menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
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
            <!-- /menu footer buttons -->
          </div>
          <!-- /.left_col.scroll-view -->
        </div>
        <!-- /.col-md-3.left_col -->
