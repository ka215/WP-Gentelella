<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
      <div class="login_wrapper">
        <div id="signin" class="animate form login_form">
          <section class="login_content">
            <form name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login', 'login_post' ); ?>" method="post">
              <h1><?php _e( 'Sign In to Plotter', WPGENT_DOMAIN ); ?></h1>
              <?php $template->the_action_template_message( 'login' ); ?>
              <?php $template->the_errors(); ?>
              <div><?php
if ( 'username' == $theme_my_login->get_option( 'login_type' ) ) {
  $placeholder = __( 'Username', WPGENT_DOMAIN );
} elseif ( 'email' == $theme_my_login->get_option( 'login_type' ) ) {
  $placeholder = __( 'E-mail', WPGENT_DOMAIN );
} else {
  $placeholder = __( 'Username or E-mail', WPGENT_DOMAIN );
} ?>                <input type="text" class="form-control" name="log" id="user_login<?php $template->the_instance(); ?>" placeholder="<?= $placeholder ?>" value="<?php $template->the_posted_value( 'log' ); ?>" required="" />
              </div>
              <div>
                <input type="password" class="form-control" name="pwd" id="user_pass<?php $template->the_instance(); ?>" placeholder="<?php _e( 'Password', WPGENT_DOMAIN ); ?>" autocomplete="off" required="" />
              </div>
              <?php do_action( 'login_form' ); ?>
              <div>
                <input type="submit" name="wp-submit" class="btn btn-default submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Sign In', WPGENT_DOMAIN ); ?>" />
                <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
                <input type="hidden" name="action" value="login" />
                <a class="reset_pass" href="/lostpassword/" rel="nofollow"><?php _e( 'Lost your password?', WPGENT_DOMAIN ); ?></a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link"><?php _e( 'New to site?', WPGENT_DOMAIN ); ?>
                  <a href="/account/#signup" class="to_register" rel="nofollow"> <?php _e( 'Create Account', WPGENT_DOMAIN ); ?> </a>
                </p>

                <p class="change_link">
                  <a href="/">Back to Top</a>
                </p>

                <div class="clearfix"></div>
                <?php /* $template->the_action_links( array( 'login' => false ) ); */ ?>
                <br />

                <?php get_template_part( 'partials/copyright' ); ?>
              </div>
            </form>
          </section>
        </div><!-- /#signin -->

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register', 'login_post' ); ?>" method="post">
              <h1><?php _e( 'Create Account', WPGENT_DOMAIN ); ?></h1>
              <?php $template->the_action_template_message( 'register' ); ?>
              <?php $template->the_errors(); ?>
              <?php if ( 'email' != $theme_my_login->get_option( 'login_type' ) ) : ?>
              <div>
                <input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="form-control" placeholder="<?php _e( 'Username', WPGENT_DOMAIN ); ?>" value="<?php $template->the_posted_value( 'user_login' ); ?>" required="" />
              </div>
              <?php endif; ?>
              <div>
                <input type="email" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="form-control" placeholder="<?php _e( 'E-mail', WPGENT_DOMAIN ); ?>" value="<?php $template->the_posted_value( 'user_email' ); ?>" required="" />
              </div>
              <?php /* do_action( 'register_form' ); */ ?>
              <div>
                <input type="password" autocomplete="off" name="pass1" id="pass1<?php $template->the_instance(); ?>" class="form-control" value="" placeholder="<?php _e( 'Password', WPGENT_DOMAIN ); ?>" required="" />
              </div>
              <div>
                <input type="password" autocomplete="off" name="pass2" id="pass2<?php $template->the_instance(); ?>" class="form-control" value="" placeholder="<?php _e( 'Confirm Password', WPGENT_DOMAIN ); ?>" required="" />
              </div>
              <p class="tml-registration-confirmation" id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'Registration confirmation will be e-mailed to you.', WPGENT_DOMAIN ) ); ?></p>
              <div>
                <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" class="btn btn-default submit" value="<?php esc_attr_e( 'Register', WPGENT_DOMAIN ); ?>" />
                <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
                <input type="hidden" name="action" value="register" />
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link"><?php _e( 'Already a member ?', WPGENT_DOMAIN ); ?>
                  <a href="/account/#signin" class="to_register"> <?php _e( 'Sign In', WPGENT_DOMAIN ); ?> </a>
                </p>

                <div class="clearfix"></div>
                <?php /* $template->the_action_links( array( 'register' => false ) ); */ ?>
                <br />

                <?php get_template_part( 'partials/copyright' ); ?>
              </div>
            </form>
          </section>
        </div><!-- /#register -->

      </div><!-- /.login_wrapper -->
