<?php
/**
 * Template part for displaying top navigation content
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$_plotter = get_query_var( 'plotter', [] );
$page_name           = $_plotter['page_name'];
$current_user_id     = $_plotter['current_user_id'];
$user_sources        = $_plotter['user_sources'];
$current_source_id   = $_plotter['current_source_id'];
$current_source_name = $_plotter['current_source_name'];
$current_user        = wp_get_current_user();
$user_avatar         = __ctl( 'lib' )::get_user_option( $current_user->ID, 'avatar' );
?>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><span class="menu-toggle-icon"></span></a>
              </div>

<?php if ( isset( $current_source_id ) ) :?>
              <div class="nav navbar-nav navbar-left">
                <form id="topnav-form" class="navbar-form navbar-left" method="post" role="choose-source" novalidate>
                  <input type="hidden" name="from_page" value="common">
                  <input type="hidden" name="source_id" value="<?= esc_attr( $current_source_id ) ?>">
                  <input type="hidden" name="post_action" id="common-post-action" value="modify">
                  <?php wp_nonce_field( 'common-setting_' . $current_user_id, '_token', true, true ); ?>
                  <div class="form-group">
                    <select id="topnav-switch-source" name="switch_source" class="form-control">
<?php foreach ( $user_sources as $_src ) : ?>
                      <option value="<?= esc_attr( $_src['id'] ) ?>" <?php selected( $_src['id'], $current_source_id ); ?>><?= esc_html( $_src['name'] ) ?></option>
<?php endforeach; ?>
                    </select>
                  </div>
                </form>
              </div>
<?php endif; ?>
              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <figure class="avatar-mini">
                      <span><?= get_avatar( $current_user->ID, 48, '', $current_user->display_name ) ?></span>
                      <figcaption class="user-name"><?= $current_user->display_name; ?></figcaption>
                    </figure>
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
