<?php
/**
 * Template part for displaying messanger
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$page_type = get_current_page_type();
$_plotter = get_query_var( 'plotter', [] );
$current_user_id = @$_plotter['current_user_id'] ?: null;

$button_width = wp_is_mobile() ? 50 : 33;

$messages = __ctl( 'lib' )->load_user_notify_logs( $current_user_id, 'unread', 'asc', 5 );
$msg_nonce = wp_create_nonce( 'messages-form_'. $current_user_id );
?>
<ul class="dropdown-menu msg_list" role="menu">
<?php foreach ( $messages as $_msg_data ) : 
  $userdata = get_userdata( (int) $_msg_data['from_user'] );
    ?>
  <li class="message-wrapper">
    <figure class="avatar-mini">
      <span>
        <?= get_avatar( $userdata->ID, 48, '', $userdata->display_name ) ?>
      </span>
    </figure>
    <div class="message-body">
      <div>
        <strong class="msg-from"><?= $userdata->display_name ?></strong>
        <span class="msg-send-time"><?php printf( _x( '%s ago', '%s = human-readable time difference', WPGENT_DOMAIN ), human_time_diff( strtotime( $_msg_data['sent_at'] ), current_time( 'timestamp' ) ) ); ?></span>
      </div>
      <div class="message">
<?php if ( $_msg_data['message_type'] !== 1 ) : ?>
        <?= strip_tags( $_msg_data['message_subject'] ) ?><br><?= strip_tags( do_shortcode( $_msg_data['message_content'] ) ) ?>
<?php else : ?>
        <?= nl2br( strip_tags( $_msg_data['message_content'] ) ) ?>
<?php endif; ?>
      </div>
    </div>
  </li>
<?php endforeach; ?>
  <li class="messenger-control" data-msg-token="<?= esc_attr( $msg_nonce ) ?>" data-from-user="<?= esc_attr( $current_user_id ) ?>">
    <button type="button" id="msgctl-see-all" class="btn btn-default btn-sm" style="width: <?= $button_width ?>%"><i class="plt-bubble-check"></i> <?= __( 'See All', WPGENT_DOMAIN ) ?></button>
    <button type="button" id="msgctl-reload"  class="btn btn-default btn-sm" style="width: <?= $button_width ?>%"><i class="plt-loop3"></i> <?= __( 'Reload', WPGENT_DOMAIN ) ?></button>
<?php if ( ! wp_is_mobile() ) : ?>
    <button type="button" id="msgctl-reside"  class="btn btn-default btn-sm" style="width: <?= $button_width ?>%"><i class="plt-square-down-right"></i> <?= __( 'Reside', WPGENT_DOMAIN ) ?></button>
<?php endif; ?>
  </li>
</ul>
