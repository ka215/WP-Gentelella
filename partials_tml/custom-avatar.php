<?php
/*
 * Plotter Core - Custom Avatar template
 */
// var_dump( $profileuser, $profileuser->data->display_name, wp_upload_dir() );
$avatar_ratings = [
  'G'  => __( 'G &#8212; Suitable for all audiences', WPGENT_DOMAIN ),
  'PG' => __( 'PG &#8212; Possibly offensive, usually for audiences 13 and above', WPGENT_DOMAIN ),
  'R'  => __( 'R &#8212; Intended for adult audiences above 17', WPGENT_DOMAIN ),
  'X'  => __( 'X &#8212; Even more mature than above', WPGENT_DOMAIN )
];
$show_rating = false;
?>
    <h4><i class="plt-file-picture blue"></i> <?= __( 'Avatar', WPGENT_DOMAIN ) ?></h4>
    <div class="ln_dotted ln_thin"></div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="avatar"><?= __( 'Upload Avatar', WPGENT_DOMAIN ) ?></label>
      <div class="col-sm-4">
        <?php
add_filter( 'pre_option_avatar_rating', '__return_null' ); // ignore ratings here
echo get_avatar( $profileuser->ID, 144, '', $profileuser->data->display_name, [ 'extra_attr' => 'no-classes' ] );
remove_filter( 'pre_option_avatar_rating', '__return_null' );
?>
        <div id="preview-image" class="avatar avatar-144 avatar-preview hide"></div>
      </div>
      <div class="col-sm-6">
        <?php
do_action( 'plotter_custom_avatar_notices' ); 
wp_nonce_field( 'plotter_custom_avatar_nonce', '_custom_avatar_nonce', false );
$remove_url = add_query_arg( [
  'action'   => 'remove-custom-avatar',
  'user_id'  => $profileuser->ID,
  '_wpnonce' => null, //$this->remove_nonce,
] );
?>
        <span class="help-block"><?= __( 'Choose an image from your local', WPGENT_DOMAIN ); ?></span>
        <div class="input-group">
          <label class="input-group-btn">
            <span class="btn btn-default">
              <?= __( 'Choose File', WPGENT_DOMAIN ) ?><input type="file" name="assign_avatar" id="custom-avatar-assign" class="hide" />
            </span>
          </label>
          <input type="text" id="preview-upfile" class="form-control" readonly="readonly" disabled />
        </div>
        <span class="spinner" id="simple-local-avatar-spinner"></span>
        <div<?php if ( empty( $profileuser->custom_avatar ) ) : ?>  class="_hide"<?php endif; ?>>
<?php /* if ( current_user_can( 'upload_files' ) && did_action( 'wp_enqueue_media' ) ) : ?><a href="#" class="button hide-if-no-js" id="simple-local-avatar-media"><?php _e( 'Choose from Media Library', 'simple-local-avatars' ); ?></a> &nbsp;<?php endif; */ ?>
          <button type="button" name="remove_avatar" data-url="<?= $remove_url ?>" class="btn btn-default" id="custom-avatar-remove"><?= __( 'Remove Avatar', WPGENT_DOMAIN ) ?></button>
        </div>
      </div>
    </div>
<?php if ( $show_rating ) : ?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="avatar"><?= __( 'Rating', WPGENT_DOMAIN ) ?></label>
      <div class="col-sm-10">
        <?php
  if ( empty( $profileuser->custom_avatar_rating ) || ! array_key_exists( $profileuser->custom_avatar_rating, $avatar_ratings ) ) {
    $profileuser->custom_avatar_rating = 'G';
  }
  foreach ( $avatar_ratings as $key => $rating ) : ?>
        <label><input type="radio" name="custom_avatar_rating" value="<?= esc_attr( $key ) ?>" <?php checked( $profileuser->custom_avatar_rating, $key, false ); ?>/> <?= esc_html( $rating ) ?></label><br />
<?php endforeach; ?>
        <span class="help-block"><?= __( 'If the local avatar is inappropriate for this site, Gravatar will be attempted.', WPGENT_DOMAIN ); ?></span>
      </div>
    </div>
<?php else : ?>
    <input type="hidden" name="custom_avatar_rating" value="G" />
<?php endif; ?>
    <div class="ln_dotted ln_thin"></div>
