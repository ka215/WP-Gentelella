<?php
/*
 * Theme My Login - login form template @plotter
 */
?>
      <div class="login_wrapper">
        <div id="signin" class="animate form login_form">
          <section class="login_content">
            <form name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login', 'login_post' ); ?>#signin" method="post">
              <h1><?= __( 'Sign In to plotter.me', WPGENT_DOMAIN ) ?></h1>
              <div class="account-notice">
                <?= $template->get_errors() ?>
                <?php /* echo strip_tags( $template->get_errors(), '<a><br>' ); */ ?>
              </div>
              <div class="form-group">
<?php 
if ( 'username' == $theme_my_login->get_option( 'login_type' ) ) {
  $placeholder = __( 'Username', WPGENT_DOMAIN );
} elseif ( 'email' == $theme_my_login->get_option( 'login_type' ) ) {
  $placeholder = __( 'E-mail', WPGENT_DOMAIN );
} else {
  $placeholder = __( 'Username or E-mail', WPGENT_DOMAIN );
} ?>
                <input type="text" class="form-control" name="log" id="user_login<?php $template->the_instance(); ?>" placeholder="<?= $placeholder ?>" value="<?php $template->the_posted_value( 'log' ); ?>" required="" />
              </div>
              <div class="form-group">
                <div class="input-group">
                  <input type="password" class="form-control" name="pwd" id="user_pass<?php $template->the_instance(); ?>" placeholder="<?php _e( 'Password', WPGENT_DOMAIN ); ?>" autocomplete="off" required="" />
                  <span class="input-group-btn">
                    <button type="button" id="toggle-password" class="btn btn-default" title="<?= __( 'Show Password', WPGENT_DOMAIN ) ?>"><i class="plt-eye"></i></button>
                  </span>
                </div>
              </div>
              <?php do_action( 'login_form' ); ?>
              <div class="form-group">
                <input type="submit" name="wp-submit" class="btn btn-primary submit" id="wp-submit-login<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Sign In', WPGENT_DOMAIN ); ?>" />
                <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
                <input type="hidden" name="action" value="login" />
                <a class="reset_pass" href="/lostpassword/" rel="nofollow"><?php _e( 'Lost your password?', WPGENT_DOMAIN ); ?></a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link"><?php _e( 'New to site?', WPGENT_DOMAIN ); ?>
                  <a href="/register/" class="to_register" rel="nofollow"> <?php _e( 'Create Account', WPGENT_DOMAIN ); ?> </a>
                </p>

                <p class="change_link">
                  <a href="/"><?= __( 'Back to Top', WPGENT_DOMAIN ) ?></a>
                </p>

                <div class="clearfix"></div>
                <?php /* $template->the_action_links( array( 'login' => false ) ); */ ?>
                <br />

                <?php /* get_template_part( 'partials/copyright' ); */ ?>
              </div>
            </form>
          </section>
        </div><!-- /#signin -->

      </div><!-- /.login_wrapper -->
