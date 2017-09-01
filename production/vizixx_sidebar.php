        <div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-vimeo"></i> <span><?= __( 'ViziXX' ) ?></span></a>
            </div>

            <div class="clearfix"></div>
            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3><?= __( 'Main Navigation' ) ?></h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-key"></i> <?= __( 'ViziXX' ) ?> <span class="fa fa-chevron"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="#"><?= __( 'Apply' ) ?></a></li>
                      <li><a href="#"><?= __( 'Cancellation' ) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-server"></i> <?= __( 'Register Process' ) ?></a></li>
                  <li><a><i class="fa fa-wifi"></i> <?= __( 'Sensor' ) ?> <span class="fa fa-chevron"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="javascript:;" onClick="location.href='vizixx_a.php';"><?= __( 'Storing Data' ) ?></a></li>
                      <li><a href="javascript:;" onClick="location.href='vizixx_b.php';"><?= __( 'Split Data' ) ?></a></li>
                      <li><a href="javascript:;" onClick="location.href='vizixx_c.php';"><?= __( 'Storage Time' ) ?></a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-plus"></i> <?= __( 'Add Widget' ) ?> <span class="fa fa-chevron"></span></a>
                    <ul class="nav child_menu">
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-th-list"></i> <?= __( 'Table' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-line-chart"></i> <?= __( 'Line Graph' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-bar-chart"></i> <?= __( 'Bar Graph' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-list"></i> <?= __( 'Text' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-toggle-on"></i> <?= __( 'Switch' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-map-o"></i> <?= __( 'Map' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-th-list"></i> <?= __( 'Table (summary)' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-line-chart"></i> <?= __( 'Line Graph (summary)' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-bar-chart"></i> <?= __( 'Bar Graph (summary)' ) ?></a></li>
                      <li><a data-toggle="modal" data-target="#registerWidget"><i class="fa fa-pie-chart"></i> <?= __( 'Pie Chart (summary)' ) ?></a></li>
                    </ul>
                  </li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a name="extention" title="Settings" disabled>
                <span class="glyphicon glyphicon-cog text-muted" style="opacity:.65"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Help">
                <span class="fa fa-question-circle-o" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="fa fa-sign-out" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>
