<?php
/**
 * Template part for displaying top navigation content
 *
 * @package WordPress
 * @subpackage WP-Gentelella
 * @since 1.0
 * @version 1.0
 */

$current_user = wp_get_current_user();
?>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
<?php /*
                <a id="menu_toggle"><i class="<?php if ( isset( $_COOKIE['current_sidebar'] ) && trim( $_COOKIE['current_sidebar'] ) === 'small' ) : ?>fa fa-angle-right<?php else : ?>fa fa-angle-left<?php endif; ?>"></i></a>
*/ ?>
                <a id="menu_toggle"><span class="menu-toggle-icon"></span></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo WPGENT_DIR; ?>production/images/img.jpg" alt=""><?= $current_user->display_name; ?>
                    <span class="plt-dots" _old="plt-circle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="/profile/"> <?php _e('Profile', WPGENT_DOMAIN); ?></a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span><?php _e('Settings', WPGENT_DOMAIN); ?></span>
                      </a>
                    </li>
                    <li><a href="javascript:;"><?php _e('Help', WPGENT_DOMAIN); ?></a></li>
                    <li><a href="<?= wp_logout_url(); ?>"><i class="plt-exit2 pull-right"></i> <?php _e('Sign Out', WPGENT_DOMAIN); ?></a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="plt-bell"></i>
                    <span class="badge bg-green">6</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo WPGENT_DIR; ?>production/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo WPGENT_DIR; ?>production/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo WPGENT_DIR; ?>production/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo WPGENT_DIR; ?>production/images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /.top_nav -->
