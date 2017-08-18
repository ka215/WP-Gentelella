        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-chevron"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Options">
                    <i class="fa fa-cogs"></i>
                    <!-- span class="fa fa-angle-down"></span -->
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="vizixx_a.php">Type A</a></li>
                    <li><a href="vizixx_b.php">Type B</a></li>
                    <li><a href="vizixx_c.php">Type C</a></li>
                    <li><a href="javascript:;"><i class="fa fa-question-circle-o pull-right"></i> Help</a></li>
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Widgets">
                    <i class="fa fa-plus"></i>
                    <!-- span class="fa fa-angle-down"></span -->
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-th-list"></i></span>
                        <span>
                          <span>Table</span>
                        </span>
                        <span class="message">
                          "timestamp" and "value" are displayed in a table in a chronological order.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-line-chart"></i></span>
                        <span>
                          <span>Line Graph</span>
                        </span>
                        <span class="message">
                          "timestamp" for horizontal axis and "value" for vertical axis. Values of multiple sensors can be displayed on the same graph.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-bar-chart"></i></span>
                        <span>
                          <span>Bar Graph</span>
                        </span>
                        <span class="message">
                          "timestamp" for horizaontal axis and "value" for vertical axis. Values of multiple sensors can be displayed on the same graph.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-list"></i></span>
                        <span>
                          <span>Text</span>
                        </span>
                        <span class="message">
                          Values transmitted are to be displayed in text format. Characters to be displayed are only from the last tansmitted values.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-toggle-on"></i></span>
                        <span>
                          <span>Switch</span>
                        </span>
                        <span class="message">
                          Values transmitted are to be displayed as an ON and OFF switch according to specific conditions. Values to be displayed are only from the last transmitted values.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-map-o"></i></span>
                        <span>
                          <span>Map</span>
                        </span>
                        <span class="message">
                          Add markers on the map according to the transmitted longitude and latitude. Markers can display past movement history.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-th-list"></i></span>
                        <span>
                          <span>Table (summary)</span>
                        </span>
                        <span class="message">
                          Accumulating sensor data under specified conditions and count the quantity of sensor data meeting the conditions. Result is to be displayed in a table.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-line-chart"></i></span>
                        <span>
                          <span>Line Graph (summary)</span>
                        </span>
                        <span class="message">
                          Accumulating sensor data under specified conditions and count the quantity of sensor data meeting the conditions. Result is to be displayed in a line graph.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-bar-chart"></i></span>
                        <span>
                          <span>Bar Graph (summary)</span>
                        </span>
                        <span class="message">
                          Accumulating sensor data under specified conditions and count the quantity of sensor data meeting the conditions. Result is to be displayed in a bar graph.
                        </span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="modal" data-target="#registerWidget">
                        <span class="image"><i class="fa fa-pie-chart"></i></span>
                        <span>
                          <span>Pie Chart (summary)</span>
                        </span>
                        <span class="message">
                          Accumulating sensor data under specified conditions and count the quantity of sensor data meeting the conditions. Result is to be displayed in a pie chart.
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Widgets</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Sensor">
                    <i class="fa fa-wifi"></i>
                    <!-- span class="fa fa-angle-down"></span -->
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;">Storing Data</a></li>
                    <li><a href="javascript:;">Split Data</a></li>
                    <li><a href="javascript:;">Storage Time</a></li>
                  </ul>
                </li>
                <li class="">
                  <a href="javascript:;" title="Register Process">
                    <i class="fa fa-tasks"></i>
                  </a>
                </li>
                <li class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="VisiXX">
                    <i class="fa fa-key"></i>
                    <!-- span class="fa fa-angle-down"></span -->
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;">Apply</a></li>
                    <li><a href="javascript:;">Cancellation</a></li>
                  </ul>
                </li>

              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
