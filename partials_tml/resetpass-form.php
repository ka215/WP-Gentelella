<?php
/*
 * Theme My Login - resetpassword form template @plotter
 */
?>
      <div class="resetpassword_wrapper">
        <div id="resetpassword" class="animate form resetpassword_form">
          <section class="resetpassword_content">
            <form name="resetpassform" id="resetpassform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'resetpass', 'login_post' ); ?>" method="post" autocomplete="off">
              <h1><?= __( 'Reset Password', WPGENT_DOMAIN ) ?></h1>
              <?php $template->the_action_template_message( 'resetpass' ); ?>
              <div class="account-notice">
                <?php /* $template->the_errors(); */ ?>
                <?= $template->get_errors() ?>
              </div>
              <div class="form-group">
                <label for="pass1"><?= __( 'New password', WPGENT_DOMAIN ) ?></label>
                <div class="wp-pwd">
                  <span class="password-input-wrapper">
                    <input type="password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1" class="form-control input" size="20" value="" autocomplete="off" aria-describedby="pass-strength-result" />
                  </span>
                  <div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?= __( 'Strength indicator', WPGENT_DOMAIN ) ?></div>
                </div>
              </div>
              <div class="form-group">
                <label for="pass2"><?= __( 'Confirm new password', WPGENT_DOMAIN ) ?></label>
                <input type="password" name="pass2" id="pass2" class="form-control input" size="20" value="" autocomplete="off" />
              </div>
              <p class="description indicator-hint"><?= wp_get_password_hint() ?></p>
<?php do_action( 'resetpassword_form' ); ?>
              <div class="form-group">
                <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" class="btn btn-primary" value="<?php esc_attr_e( 'Reset Password', WPGENT_DOMAIN ); ?>" />
                <input type="hidden" id="user_login" value="<?php echo esc_attr( $GLOBALS['rp_login'] ); ?>" autocomplete="off" />
                <input type="hidden" name="rp_key" value="<?php echo esc_attr( $GLOBALS['rp_key'] ); ?>" />
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
                <input type="hidden" name="action" value="resetpass" />
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">
                  <a href="/"><?= __( 'Back to Top', WPGENT_DOMAIN ) ?></a>
                </p>

                <div class="clearfix"></div>
                <?php $template->the_action_links( [ 'login' => false, 'register' => false, 'lostpassword' => false ] ); ?>
                <br />

                <?php get_template_part( 'partials/copyright' ); ?>
              </div>
            </form>
          </section>
        </div><!-- /#resetpassword -->

      </div><!-- /.resetpassword_wrapper -->
