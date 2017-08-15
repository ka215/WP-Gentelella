        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row row-flex row-flex-wrap">

            <div class="col-md-12">
              <h2 style="font-weight: bold; margin-right: 30px;">Chart.js</h2>
              <p hidden>有名なオープンソースのグラフ描画ライブラリ。グラフの種類や機能は少ないが、シンプルな記述で実装が容易。描画時にアニメーションが有る。レスポンシブ対応で、モダンブラウザでの閲覧に問題はない。</p>
              <p>A famous open source graph drawing library. Although there are few graph types and functions, it is easy to implement with a simple coding. There is animation when drawing. Responsive correspondence, there is no problem with browsing with a modern browser.</p>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-6">
              <div class="x_panel tile flex-col __fixed_height_320">
                <div class="x_title">
                  <h2>Line Graph</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content flex-grow">
                  <canvas id="lineChart"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col -->

            <div class="col-md-6 col-sm-6 col-xs-6">
              <div class="x_panel tile flex-col __fixed_height_320">
                <div class="x_title">
                  <h2>Bar Graph</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content flex-grow">
                  <canvas id="mybarChart"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col -->

            <div class="col-md-5 col-sm-5 col-xs-6">
              <div class="x_panel tile flex-col __fixed_height_320">
                <div class="x_title">
                  <h2>Doughnut Pie Chart</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content flex-grow">
                  <canvas id="canvasDoughnut"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col -->

            <div class="col-md-5 col-sm-5 col-xs-6">
              <div class="x_panel tile flex-col __fixed_height_320">
                <div class="x_title">
                  <h2>Pie Chart</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content flex-grow">
                  <canvas id="pieChart"></canvas>
                </div>
              </div>
            </div>
            <!-- /.col -->

            <div class="col-md-2 col-sm-2 col-xs-6">
              <div class="x_panel addable_block flex-col">
                <a href="javascript:;" class="add_content flex-grow" title="Add New Widget" data-toggle="modal" data-target="#registerWidget"></a>
              </div>
            </div>
            <!-- /.col -->

          </div>
          <!-- /.row -->
        </div>
        <!-- /page content -->
