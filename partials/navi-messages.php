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
$button_width = wp_is_mobile() ? 50 : 33;

/*
<li role="presentation" class="dropdown">
  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
    <i class="plt-bell"></i>
    <span class="badge bg-red">99</span>
  </a>
*/
?>
<ul class="dropdown-menu msg_list" role="menu">
<?php for($i=0;$i<5;$i++): ?>
  <li class="message-wrapper">
    <figure class="avatar-mini">
      <span>
        <img alt="User Name" src="<?php echo WPGENT_DIR; ?>production/images/img.jpg" class="avatar avatar-48 photo" height="48" width="48" />
      </span>
    </figure>
    <div class="message-body">
      <div>
        <strong class="msg-from">Plotter</strong>
        <span class="msg-send-time">3 mins ago</span>
      </div>
      <div class="message">
        I'm the stack context where notices will be placed. I'm position: relative, so the notices will be positioned relative to me. My overflow is set to auto, so the notices won't show beyond my borders.
      </div>
    </div>
  </li>
<?php endfor; ?>
  <li class="messenger-control">
    <button type="button" id="msgctl-see-all" class="btn btn-default btn-sm" style="width: <?= $button_width ?>%"><i class="plt-bubble-check"></i> <?= __( 'See All', WPGENT_DOMAIN ) ?></button>
    <button type="button" id="msgctl-reload"  class="btn btn-default btn-sm" style="width: <?= $button_width ?>%"><i class="plt-loop3"></i> <?= __( 'Reload', WPGENT_DOMAIN ) ?></button>
<?php if ( ! wp_is_mobile() ) : ?>
    <button type="button" id="msgctl-reside"  class="btn btn-default btn-sm" style="width: <?= $button_width ?>%"><i class="plt-square-down-right"></i> <?= __( 'Reside', WPGENT_DOMAIN ) ?></button>
<?php endif; ?>
  </li>
</ul>
