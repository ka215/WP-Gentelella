<?php
/*
 * Plotter Core - Custom Avatar template
 */
$avatar_ratings = [
  'G'  => __( 'Suitable for all audiences', WPGENT_DOMAIN ),
  'PG' => __( 'Possibly offensive, usually for audiences 13 and above', WPGENT_DOMAIN ),
  'R'  => __( 'Intended for adult audiences above 17', WPGENT_DOMAIN ),
  'X'  => __( 'Even more mature than above', WPGENT_DOMAIN )
];
$show_rating = false;
$max_upload_size = size_format( $this->max_upload_size );
$current_user_options = __ctl( 'lib' )::get_user_option( $profileuser->ID );
if ( ! isset( $current_user_options['avatar_rating'] ) || empty( $current_user_options['avatar_rating'] ) ) {
  $selected_avatar_rating = 'G';
} else {
  $selected_avatar_rating = $current_user_options['avatar_rating'];
}
?>
    <h4><i class="plt-file-picture blue"></i> <?= __( 'Avatar', WPGENT_DOMAIN ) ?></h4>
    <div class="ln_dotted ln_thin"></div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="upload-avatar"><?= __( 'Upload Avatar', WPGENT_DOMAIN ) ?></label>
      <div id="avatar-viewer" class="col-sm-4">
        <?php
add_filter( 'pre_option_avatar_rating', '__return_null' ); // ignore ratings here
echo get_avatar( $profileuser->ID, 144, '', $profileuser->data->display_name );
remove_filter( 'pre_option_avatar_rating', '__return_null' );
?>
        <div class="preview-container hide"></div>
      </div>
      <div class="col-sm-6">
        <?php
do_action( 'plotter_custom_avatar_notices' ); 
wp_nonce_field( 'plotter_custom_avatar_nonce', '_custom_avatar_nonce', false );
?>
        <span class="help-block"><?= __( 'Choose an image from your local', WPGENT_DOMAIN ); ?> (<?php printf( __( 'Uploadable max file size: %s', WPGENT_DOMAIN ), $max_upload_size ); ?>)</span>
        <div class="input-group">
          <label class="input-group-btn">
            <span id="upload-avatar" class="btn btn-default">
              <?= __( 'Choose File', WPGENT_DOMAIN ) ?><input type="file" name="assign_avatar" id="custom-avatar-assign" class="hide" />
            </span>
          </label>
          <input type="text" id="preview-upfile" class="form-control" readonly="readonly" value="" disabled />
        </div>
        <span class="spinner" id="simple-local-avatar-spinner"></span>
        <div<?php if ( ! isset( $current_user_options['avatar'] ) ) : ?>  class="hide"<?php endif; ?>>
          <button type="button" name="remove_avatar" class="btn btn-default" id="custom-avatar-remove"><i class="plt-checkbox-unchecked2 gray"></i> <?= __( 'Remove Avatar', WPGENT_DOMAIN ) ?></button>
          <input type="hidden" name="remove_custom_avatar" id="remove-avatar-action" value="false" />
        </div>
      </div>
    </div>
<?php if ( $show_rating ) : ?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="avatar"><?= __( 'Rating', WPGENT_DOMAIN ) ?></label>
      <div class="col-sm-10">
<?php foreach ( $avatar_ratings as $_key => $rating ) : ?>
        <label class="avatar-ratings"><input type="radio" name="custom_avatar_rating" value="<?= esc_attr( $_key ) ?>" <?= checked( $selected_avatar_rating, $_key, false ); ?>/><span class="rating-key"><?= esc_html( $_key ) ?></span><span class="rating-desc"><?= esc_html( $rating ) ?></span></label>
<?php endforeach; ?>
        <span class="help-block"><?= __( 'If the local avatar is inappropriate for this site, Gravatar will be attempted.', WPGENT_DOMAIN ); ?></span>
      </div>
    </div>
<?php else : ?>
    <input type="hidden" name="custom_avatar_rating" value="<?= esc_attr( $selected_avatar_rating ) ?>" />
<?php endif; ?>
    <div class="ln_dotted ln_thin"></div>
