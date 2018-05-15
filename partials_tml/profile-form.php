<?php
/*
 * Theme My Login - profile form template @plotter
 */
?>
<div class="tml tml-profile" id="theme-my-login<?php $template->the_instance(); ?>">
  <div class="profile-notice"><?php 
    $template->the_action_template_message( 'profile' );
    $template->the_errors(); ?></div>
  <form id="your-profile" action="<?php $template->the_action_url( 'profile', 'login_post' ); ?>" method="post" class="form-horizontal"<?php do_action( 'user_edit_form_tag' ); ?>>
    <?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
    <input type="hidden" name="from" value="profile" />
    <input type="hidden" name="checkuser_id" value="<?= $current_user->ID; ?>" />
    <input type="hidden" name="admin_bar_front" value="" />

    <h4><i class="plt-profile blue"></i> <?php _e( 'Personal Information', WPGENT_DOMAIN ); ?></h4>
    <div class="ln_dotted ln_thin"></div>
<?php /*
    <label for="admin_bar_front"><?php _e( 'Toolbar', WPGENT_DOMAIN )?></label>
    <label for="admin_bar_front"><input type="checkbox" name="admin_bar_front" id="admin_bar_front" value="1"<?php checked( _get_admin_bar_pref( 'front', $profileuser->ID ) ); ?> />
      <?php _e( 'Show Toolbar when viewing site', WPGENT_DOMAIN ); ?></label> */
    do_action( 'personal_options', $profileuser );
    do_action( 'profile_personal_options', $profileuser ); ?>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="first_name"><?= __( 'Name', WPGENT_DOMAIN ); ?></label>
      <div class="col-sm-5">
        <input type="text" name="first_name" id="first_name" class="form-control regular-text" value="<?= esc_attr( @$profileuser->first_name ); ?>" placeholder="<?= __( 'First Name', WPGENT_DOMAIN ); ?>" />
      </div>
      <div class="col-sm-5">
        <input type="text" name="last_name" id="last_name" class="form-control regular-text" value="<?= esc_attr( @$profileuser->last_name ); ?>" placeholder="<?= __( 'Last Name', WPGENT_DOMAIN ); ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="pseudonym"><?= __( 'Pseudonym', WPGENT_DOMAIN ); ?> <span class="required"></span></label>
      <div class="col-sm-7">
        <input type="text" name="nickname" id="pseudonym" class="form-control regular-text" value="<?= esc_attr( @$profileuser->nickname ); ?>" placeholder="<?= __( 'Pseudonymous as like nickname', WPGENT_DOMAIN ); ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="display_name"><?php _e( 'Display Name', WPGENT_DOMAIN ); ?> <span class="required"></span></label>
      <div class="col-sm-7">
        <select name="display_name" id="display_name" class="form-control"><?php
$public_display = [];
$public_display['display_nickname']  = @$profileuser->nickname ?: '';
$public_display['display_username']  = @$profileuser->user_login ?: '';

if ( ! empty( $profileuser->first_name ) )
  $public_display['display_firstname'] = $profileuser->first_name;

if ( ! empty( $profileuser->last_name ) )
  $public_display['display_lastname'] = $profileuser->last_name;

if ( ! empty( $profileuser->first_name ) && ! empty( $profileuser->last_name ) ) {
  $public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
  $public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
}

if ( ! empty( $profileuser->display_name ) && ! in_array( $profileuser->display_name, $public_display, true ) ) // Only add this if it isn't duplicated elsewhere (:> 別の場所に複製されていない場合にのみ追加します
  $public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;

$public_display = array_map( 'trim', $public_display );
$public_display = array_unique( $public_display );

foreach ( $public_display as $id => $item ) : ?>
          <option <?php if ( ! empty( $profileuser->display_name ) ) { selected( $profileuser->display_name, $item ); } ?>><?= esc_html( $item ); ?></option>
<?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="email"><?= __( 'E-mail', WPGENT_DOMAIN ); ?> <span class="required"></span></label>
      <div class="col-sm-8">
        <input type="text" name="email" id="email" class="form-control regular-text" value="<?= esc_attr( @$profileuser->user_email ); ?>" placeholder="<?= __( 'New E-mail', WPGENT_DOMAIN ); ?>" />
<?php $new_email = get_option( $current_user->ID . '_new_email' );
if ( $new_email && $new_email['newemail'] != $current_user->user_email ) : ?>
        <div class="updated inline">
          <p><?php printf(
                   __( 'There is a pending change of your e-mail to %1$s. <a href="%2$s">Cancel</a>', WPGENT_DOMAIN ), // %1$sへのEメール変更は保留中です。
                   '<code>' . $new_email['newemail'] . '</code>',
                   esc_url( self_admin_url( 'profile.php?dismiss=' . $current_user->ID . '_new_email' ) )
                   ); ?></p>
        </div>
<?php endif; ?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="url"><?= __( 'Website', WPGENT_DOMAIN ); ?></label>
      <div class="col-sm-10">
        <input type="text" name="url" id="url" class="form-control regular-text code" value="<?= esc_attr( @$profileuser->user_url ); ?>" placeholder="<?= __( 'Your Website URL', WPGENT_DOMAIN ); ?>" />
      </div>
    </div>
<?php foreach ( wp_get_user_contact_methods() as $name => $desc ) : ?>
    <div class="form-group tml-user-contact-method-<?= esc_attr( $name ) ?>-wrap">
      <label class="col-sm-2 control-label" for="<?= esc_attr( $name ) ?>"><?php echo apply_filters( 'user_'.$name.'_label', $desc ); ?></label>
      <div class="col-sm-10">
        <input type="text" name="<?= esc_attr( $name ) ?>" id="<?= esc_attr( $name ) ?>" class="form-control regular-text" value="<?= esc_attr( $profileuser->$name ); ?>" />
      </div>
    </div>
<?php endforeach; ?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="biography"><?= __( 'Biographical Info.', WPGENT_DOMAIN ); ?></label>
      <div class="col-sm-10">
        <textarea name="description" id="biography" rows="5" cols="30" class="form-control" placeholder="<?= __( 'About Yourself', WPGENT_DOMAIN ) ?>"><?= esc_html( @$profileuser->description ); ?></textarea>
        <span class="help-block"><?= __( 'Share a little biographical information to fill out your profile. This may be shown publicly.', WPGENT_DOMAIN ) ?></span>
      </div>
    </div>
    <div class="clearfix"></div>
<?php do_action( 'show_user_profile', $profileuser ); ?>
    <div class="clearfix"></div>
<?php
$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
if ( $show_password_fields ) : ?>
    <h4><i class="plt-lock3 blue"></i> <?= __( 'Account Management', WPGENT_DOMAIN ); ?></h4>
    <div class="ln_dotted ln_thin"></div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="user_login"><?= __( 'Username', WPGENT_DOMAIN ); ?></label>
      <div class="col-sm-10">
        <button type="button" id="delete-account" class="btn btn-default pull-right" data-nonce="<?= wp_create_nonce( 'plotter_delete_account_' . @$profileuser->ID ) ?>"><?= __( 'Delete Account', WPGENT_DOMAIN ) ?></button>
        <p id="user_login" class="form-control-static"><?= esc_html( @$profileuser->user_login ); ?></p>
        <input type="hidden" name="user_login" value="<?= esc_attr( @$profileuser->user_login ); ?>" />
        <span class="help-block"><?= __( 'Username cannot be changed.', WPGENT_DOMAIN ); ?></span>
      </div>
    </div>
    <div class="form-group user-pass1-wrap" id="password">
      <label class="col-sm-2 control-label" for="pass1"><?= __( 'New Password', WPGENT_DOMAIN ); ?></label>
      <div class="col-sm-10">
        <input class="hidden" value=" " /><!-- #24364 workaround -->
        <button type="button" id="generate-pw" class="btn btn-default"><?= __( 'Generate Password', WPGENT_DOMAIN ); ?></button>
        <div id="passwd-ctrl" class="hide">
          <div class="col-sm-5">
            <input type="password" name="pass1" id="pass1" class="form-control regular-text" value="" autocomplete="off" data-pw="<?= esc_attr( wp_generate_password( 24 ) ) ?>" aria-describedby="pass-strength-result" />
            <input type="hidden" name="pass2" id="pass2" class="form-control" value="" />
          </div>
          <div id="pwd-strength-notice" class="col-sm-2 center-block">
            <div class="hide">
              <div style="display:none" id="pass-strength-result" aria-live="polite"></div>
            </div>
          </div>
          <div class="col-sm-3 pull-right">
            <button type="button" id="toggle-passwd" class="btn btn-default" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide Password', WPGENT_DOMAIN ); ?>">
              <i class="plt-eye-blocked"></i> <span class="text"><?php _e( 'Hide', WPGENT_DOMAIN ); ?></span>
            </button>
            <button type="button" id="cancel-passwd" class="btn btn-dark" aria-label="<?php esc_attr_e( 'Cancel password change', WPGENT_DOMAIN ); ?>">
              <span class="text"><?php _e( 'Cancel', WPGENT_DOMAIN ); ?></span>
            </button>
          </div>
        </div><!-- /#passwd-ctrl -->
      </div>
    </div>
    <div class="form-group pw-weak hide">
      <div class="col-sm-10 col-sm-offset-2">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="pw_weak" id="pw_weak" class="pw-checkbox" /> <?= __( 'Confirm use of weak password', WPGENT_DOMAIN ); ?>
          </label>
        </div>
      </div>
    </div>
<?php endif; ?>
    <div class="clearfix" style="margin-bottom: 2em;"></div>
    <div class="ln_dotted ln_thin"></div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="action" value="profile" />
        <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
        <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
        <button type="submit" name="submit" id="submit" class="btn btn-primary"><?= __( 'Update Profile', WPGENT_DOMAIN ) ?></button>
      </div>
    </div>
  </form>
</div>
