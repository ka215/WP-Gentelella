<?php
/*
 * Theme My Login - lostpassword form template @plotter
 */
?>
      <div class="lostpassword_wrapper">
        <div id="lostpassword" class="animate form lostpassword_form">
          <section class="lostpassword_content">
            <form name="lostpasswordform" id="lostpasswordform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'lostpassword', 'login_post' ); ?>" method="post">
              <h1><?= __( 'Lost Password?', WPGENT_DOMAIN ) ?></h1>
              <?php $template->the_action_template_message( 'lostpassword' ); ?>
              <div class="account-notice">
                <?php /* $template->the_errors(); */ ?>
                <?= $template->get_errors() ?>
              </div>
              <div class="form-group">
<?php 
if ( 'email' == $theme_my_login->get_option( 'login_type' ) ) {
  $placeholder = __( 'E-mail', WPGENT_DOMAIN );
} else {
  $placeholder = __( 'Username or E-mail', WPGENT_DOMAIN );
} ?>
                <input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="form-control" value="<?php $template->the_posted_value( 'user_login' ); ?>" placeholder="<?= $placeholder ?>" required="" />
              </div>
<?php do_action( 'lostpassword_form' ); ?>
              <div class="form-group">
                <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" class="btn btn-primary submit" value="<?php esc_attr_e( 'Get New Password', WPGENT_DOMAIN ); ?>" />
                <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'lostpassword' ); ?>" />
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
                <input type="hidden" name="action" value="lostpassword" />
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link"><?php _e( 'Already a member ?', WPGENT_DOMAIN ); ?>
                  <a href="/account/" class="to_register"> <?php _e( 'Sign In', WPGENT_DOMAIN ); ?> </a>
                </p>

                <p class="change_link"><?php _e( 'New to site?', WPGENT_DOMAIN ); ?>
                  <a href="/register/" class="to_register" rel="nofollow"> <?php _e( 'Create Account', WPGENT_DOMAIN ); ?> </a>
                </p>

                <p class="change_link">
                  <a href="/"><?= __( 'Back to Top', WPGENT_DOMAIN ) ?></a>
                </p>

                <div class="clearfix"></div>
                <?php /* $template->the_action_links( array( 'lostpassword' => false ) ); */ ?>
                <br />

                <?php get_template_part( 'partials/copyright' ); ?>
              </div>
            </form>
          </section>
        </div><!-- /#lostpassword -->

      </div><!-- /.lostpassword_wrapper -->
