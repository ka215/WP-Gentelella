<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
      <div class="login_wrapper">
        <div id="lostpassword" class="animate form lostpassword_form">
          <section class="login_content">
            <form name="lostpasswordform" id="lostpasswordform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'lostpassword', 'login_post' ); ?>" method="post">
              <h1><?php _e( 'Lost Password?', WPGENT_DOMAIN ); ?></h1>
              <?php $template->the_action_template_message( 'lostpassword' ); ?>
              <?php $template->the_errors(); ?>
              <div><?php
if ( 'email' == $theme_my_login->get_option( 'login_type' ) ) {
  $placeholder = __( 'E-mail:', WPGENT_DOMAIN );
} else {
  $placeholder = __( 'Username or E-mail:', WPGENT_DOMAIN );
} ?>              <input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="form-control" value="<?php $template->the_posted_value( 'user_login' ); ?>" placeholder="<?= $placeholder ?>" required="" />
              </div>
              <?php do_action( 'lostpassword_form' ); ?>
              <div>
                <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" class="btn btn-default submit" value="<?php esc_attr_e( 'Get New Password', WPGENT_DOMAIN ); ?>" />
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
                  <a href="/account/#signup" class="to_register" rel="nofollow"> <?php _e( 'Create Account', WPGENT_DOMAIN ); ?> </a>
                </p>

                <p class="change_link">
                  <a href="/">Back to Top</a>
                </p>

                <div class="clearfix"></div>
                <?php /* $template->the_action_links( array( 'lostpassword' => false ) ); */ ?>
                <br />

                <?php get_template_part( 'partials/copyright' ); ?>
              </div>
            </form>
          </section>
        </div><!-- /#lostpassword -->

      </div><!-- /.login_wrapper -->
