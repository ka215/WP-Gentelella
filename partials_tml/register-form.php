<?php
/*
 * Theme My Login - register form template @plotter
 */
?>
      <div class="register_wrapper">
        <div id="register" class="animate form registration_form">
          <section class="register_content">
            <form name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register', 'login_post' ); ?>" method="post">
              <h1><?= __( 'Create Account', WPGENT_DOMAIN ) ?></h1>
              <p class="message"><?= __( 'Register for plotter.me', WPGENT_DOMAIN ); ?></p>
              <?php /* $template->the_action_template_message( 'register' ); */ ?>
              <div class="account-notice">
                <?php /* $template->the_errors(); */ ?>
                <?= $template->get_errors() ?>
              </div>
<?php if ( 'email' != $theme_my_login->get_option( 'login_type' ) ) : ?>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon" title="<?= __( 'Username', WPGENT_DOMAIN ) ?>"><i class="plt-user"></i></span>
                  <input type="text" name="user_login" id="user_name<?php $template->the_instance(); ?>" class="form-control" placeholder="<?= __( 'Username', WPGENT_DOMAIN ) ?>" value="<?php $template->the_posted_value( 'user_login' ); ?>" required="" />
                </div>
              </div>
<?php endif; ?>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon" title="<?= __( 'E-mail', WPGENT_DOMAIN ) ?>"><i class="plt-mail2"></i></span>
                  <input type="email" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="form-control" placeholder="<?= __( 'E-mail', WPGENT_DOMAIN ) ?>" value="<?php $template->the_posted_value( 'user_email' ); ?>" required="" />
                </div>
              </div>
<?php /* do_action( 'register_form' ); */ ?>
              <div class="form-group">
                <div class="input-group">
                  <input type="password" name="pass1" id="pass1<?php $template->the_instance(); ?>" class="form-control" placeholder="<?= __( 'Password', WPGENT_DOMAIN ) ?>" value="" required="" autocomplete="off" />
                  <span class="input-group-btn">
                    <button type="button" id="toggle-password" class="btn btn-default" title="<?= __( 'Show Password', WPGENT_DOMAIN ) ?>"><i class="plt-eye"></i></button>
                  </span>
                </div>
                <input type="hidden" name="pass2" id="pass2<?php $template->the_instance(); ?>" class="form-control" value="" />
              </div>
              <div class="form-group">
                <p class="tml-registration-confirmation" id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'Registration confirmation will be e-mailed to you.', WPGENT_DOMAIN ) ); ?></p>
              </div>
              <div class="form-group">
                <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" class="btn btn-primary" value="<?php esc_attr_e( 'Register', WPGENT_DOMAIN ); ?>" />
                <input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
                <input type="hidden" name="action" value="register" />
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link"><?php _e( 'Already a member ?', WPGENT_DOMAIN ); ?>
                  <a href="/account/" class="to_register"> <?php _e( 'Sign In', WPGENT_DOMAIN ); ?> </a>
                </p>

                <p class="change_link">
                  <a href="/"><?= __( 'Back to Top', WPGENT_DOMAIN ) ?></a>
                </p>

                <div class="clearfix"></div>
                <?php /* $template->the_action_links( array( 'register' => false ) ); */ ?>
                <br />

                <?php get_template_part( 'partials/copyright' ); ?>
              </div>
            </form>
          </section>
        </div><!-- /#register -->

      </div><!-- /.register_wrapper -->
