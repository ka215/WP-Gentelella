<?php
/**
 * Template part for displaying messages content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$_plotter = get_query_var( 'plotter', [] );
$page_name           = @$_plotter['page_name'] ?: '';
$current_user_id     = @$_plotter['current_user_id'] ?: null;

$maxContentLength    = 2000; // =admin; user: 250?

//__ctl( 'lib' )::replace_user_message( $current_user_id, '5d11134d' );

$messages = __ctl( 'lib' )::load_user_notify_logs( $current_user_id, 'all' );
foreach ( $messages as $_id => $_data ) {
  $_usrdata = get_userdata( $_data['from_user'] );
  $messages[$_id]['from_user_name']  = $_usrdata->display_name;
  $messages[$_id]['human_time_diff'] = human_time_diff( strtotime( $_data['sent_at'] ), current_time( 'timestamp' ) );
}
?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class( 'flex-container' ); ?>>
            <div class="x_panel panel-primary">
              <div class="x_title">
                <h3><i class="plt-bubble-check blue"></i> <?= __( 'All Messages', WPGENT_DOMAIN ) ?></h3>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form id="messengerForm" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                  <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                  <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                  <input type="hidden" name="from_user" value="<?= esc_attr( $current_user_id ) ?>">
                  <input type="hidden" name="parent_id" id="<?= esc_attr( $page_name ) ?>-parent-id" value="">
                  <input type="hidden" id="message_to_user" name="to_user" value="0" />
                  <input type="hidden" id="message_to_team" name="to_team" value="" />
                  <?php wp_nonce_field( $page_name . '-form_' . $current_user_id, '_token', true, true ); ?>
<?php if ( ! current_user_can( 'manage_options' ) ) : ?>
                  <div class="messages-tabs" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="messageTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation">
                        <a href="#tab-content1" role="tab" id="all-messages-tab" data-toggle="tab" aria-expanded="false"><?= __( 'All', WPGENT_DOMAIN ) ?></a>
                      </li>
                      <li role="presentation" class="active">
                        <a href="#tab-content2" role="tab" id="unread-messages-tab" data-toggle="tab" aria-expanded="true"><?= __( 'Unread', WPGENT_DOMAIN ) ?></a>
                      </li>
                      <li role="presentation" class="">
                        <a href="#tab-content3" role="tab" id="read-messages-tab" data-toggle="tab" aria-expanded="false"><?= __( 'Already Read', WPGENT_DOMAIN ) ?></a>
                      </li>
                      <li role="presentation" class="no-tab">
                        <button type="button" class="btn btn-default btn-to-reload pull-right"><i class="plt-loop3"></i> <?= __( 'Reload', WPGENT_DOMAIN ) ?></button>
                      </li>
                    </ul>
                    <div id="messageTabContent" class="tab-content">
                      <div role="tabpanel" class="tab-pane fade" id="tab-content1" aria-labelledby="all-tab">
                        <ul class="msg_list msg_list_lg">
<?php foreach( $messages as $_msg_id => $_msg_data ) : ?>
                          <li data-msg-id="<?= $_msg_id ?>" class="message-wrapper">
                            <figure class="avatar-small">
                              <span>
                                <?= get_avatar( $_msg_data['from_user'], 64, '', $_msg_data['from_user_name'] ) ?>
                              </span>
                            </figure>
                            <div class="message-body<?php if ( ! $_msg_data['unread'] ) : ?> already-read<?php endif; ?>">
                              <div>
                                <strong class="msg-from"><?= $_msg_data['from_user_name'] ?></strong>
                                <span class="msg-send-time pull-right" title="<?= $_msg_data['sent_at'] ?>"><?php printf( _x( '%s ago', '%s = human-readable time difference', WPGENT_DOMAIN ), $_msg_data['human_time_diff'] ); ?></span>
                              </div>
<?php if ( ! empty( $_msg_data['message_subject'] ) ) : ?>
                              <h5 class="message-subject"><?= esc_html( strip_tags( $_msg_data['message_subject'] ) ) ?></h5>
<?php endif; ?>
                              <div class="message">
<?php     if ( $_msg_data['message_type'] !== 1 ) : ?>
                                <?= nl2br( do_shortcode( $_msg_data['message_content'] ) ) ?>
<?php     else : ?>
                                <?= nl2br( strip_tags( $_msg_data['message_content'], '<a><strong>' ) ) ?>
<?php     endif; ?>
                              </div>
                              <div class="message-footer">
<?php   if ( $_msg_data['unread'] ) : ?>
                                <button type="button" class="btn btn-default btn-sm btn-to-read"><i class="plt-eye-plus"></i> <?= __( 'Mark as Read', WPGENT_DOMAIN ) ?></button>
<?php   else : ?>
                                <button type="button" class="btn btn-default btn-sm btn-to-unread"><i class="plt-eye-minus"></i> <?= __( 'Revert to Unread', WPGENT_DOMAIN ) ?></button>
<?php   endif; ?>
<?php   if ( $_msg_data['message_type'] == 1 ) : ?>
                                <button type="button" class="btn btn-primary btn-sm btn-to-reply"><i class="plt-reply"></i> <?= __( 'Reply', WPGENT_DOMAIN ) ?></button>
<?php   endif; ?>
                              </div>
                            </div>
                          </li>
<?php endforeach; ?>
                        </ul><!-- /.msg_list -->
                      </div><!-- /#tab-content1 -->
                      <div role="tabpanel" class="tab-pane fade active in" id="tab-content2" aria-labelledby="unread-tab">
                        <ul class="msg_list msg_list_lg">
<?php $count = 0;
      foreach( $messages as $_msg_id => $_msg_data ) : 
        if ( $_msg_data['unread'] ) : ?>
                          <li data-msg-id="<?= $_msg_id ?>" class="message-wrapper">
                            <figure class="avatar-small">
                              <span>
                                <?= get_avatar( $_msg_data['from_user'], 64, '', $_msg_data['from_user_name'] ) ?>
                              </span>
                            </figure>
                            <div class="message-body">
                              <div>
                                <strong class="msg-from"><?= $_msg_data['from_user_name'] ?></strong>
                                <span class="msg-send-time pull-right" title="<?= $_msg_data['sent_at'] ?>"><?php printf( _x( '%s ago', '%s = human-readable time difference', WPGENT_DOMAIN ), $_msg_data['human_time_diff'] ); ?></span>
                              </div>
<?php     if ( ! empty( $_msg_data['message_subject'] ) ) : ?>
                              <h5 class="message-subject"><?= esc_html( strip_tags( $_msg_data['message_subject'] ) ) ?></h5>
<?php     endif; ?>
                              <div class="message">
<?php     if ( $_msg_data['message_type'] !== 1 ) : ?>
                                <?= nl2br( do_shortcode( $_msg_data['message_content'] ) ) ?>
<?php     else : ?>
                                <?= nl2br( strip_tags( $_msg_data['message_content'], '<a><strong>' ) ) ?>
<?php     endif; ?>
                              </div>
                              <div class="message-footer">
                                <button type="button" class="btn btn-default btn-sm btn-to-read"><i class="plt-eye-plus"></i> <?= __( 'Mark as Read', WPGENT_DOMAIN ) ?></button>
<?php     if ( $_msg_data['message_type'] == 1 ) : ?>
                                <button type="button" class="btn btn-primary btn-sm btn-to-reply"><i class="plt-reply"></i> <?= __( 'Reply', WPGENT_DOMAIN ) ?></button>
<?php     endif; ?>
                              </div>
                            </div>
                          </li>
<?php   $count++;
        endif;
      endforeach;
      if ( $count == 0 ) : ?>
                          <li class="message-wrapper">
                            <p class="no-list"><?= __( 'No Messages', WPGENT_DOMAIN ) ?></p>
                          </li>
<?php endif; ?>
                        </ul><!-- /.msg_list -->
                      </div><!-- /#tab-content2 -->
                      <div role="tabpanel" class="tab-pane fade" id="tab-content3" aria-labelledby="already-read-tab">
                        <ul class="msg_list msg_list_lg">
<?php $count = 0;
      foreach( $messages as $_msg_id => $_msg_data ) : 
        if ( ! $_msg_data['unread'] ) : ?>
                          <li data-msg-id="<?= $_msg_id ?>" class="message-wrapper">
                            <figure class="avatar-small">
                              <span>
                                <?= get_avatar( $_msg_data['from_user'], 64, '', $_msg_data['from_user_name'] ) ?>
                              </span>
                            </figure>
                            <div class="message-body">
                              <div>
                                <strong class="msg-from"><?= esc_html( $_msg_data['from_user_name'] ) ?></strong>
                                <span class="msg-send-time pull-right" title="<?= $_msg_data['sent_at'] ?>"><?php printf( _x( '%s ago', '%s = human-readable time difference', WPGENT_DOMAIN ), $_msg_data['human_time_diff'] ); ?></span>
                              </div>
<?php if ( ! empty( $_msg_data['message_subject'] ) ) : ?>
                              <h5 class="message-subject"><?= esc_html( strip_tags( $_msg_data['message_subject'] ) ) ?></h5>
<?php endif; ?>
                              <div class="message">
<?php     if ( $_msg_data['message_type'] !== 1 ) : ?>
                                <?= nl2br( do_shortcode( $_msg_data['message_content'] ) ) ?>
<?php     else : ?>
                                <?= nl2br( strip_tags( $_msg_data['message_content'], '<a><strong>' ) ) ?>
<?php     endif; ?>
                              </div>
                              <div class="message-footer">
                                <button type="button" class="btn btn-default btn-sm btn-to-unread"><i class="plt-eye-minus"></i> <?= __( 'Revert to Unread', WPGENT_DOMAIN ) ?></button>
<?php     if ( $_msg_data['message_type'] == 1 ) : ?>
                                <button type="button" class="btn btn-primary btn-sm btn-to-reply"><i class="plt-reply"></i> <?= __( 'Reply', WPGENT_DOMAIN ) ?></button>
<?php     endif; ?>
                              </div>
                            </div>
                          </li>
<?php   $count++;
        endif;
      endforeach;
      if ( $count == 0 ) : ?>
                          <li class="message-wrapper">
                            <p class="no-list"><?= __( 'No Messages', WPGENT_DOMAIN ) ?></p>
                          </li>
<?php endif; ?>
                        </ul><!-- /.msg_list -->
                      </div><!-- /#tab-content3 -->
                    </div><!-- /.tab-content -->
                  </div><!-- /role:tabpanel -->
                  <div class="clearfix"></div>
<?php endif; ?>
<?php if ( current_user_can( 'manage_options' ) ) : ?>
                  <h4><i class="plt-bubble-lock blue"></i> <?= __( 'Message creation console for administrator', WPGENT_DOMAIN ) ?></h4>
                  <div class="ln_dotted ln_thin"></div>
                  <div class="admin-item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="message_type"><?= __( 'Message Type', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                    <div class="col-md-10 col-sm-10 col-xs-12">
                      <label class="radio-inline">
                        <input type="radio" id="message_type" name="type" value="0" checked="checked"> <?= __( 'Broadcast Message', WPGENT_DOMAIN ) ?>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" id="message_type1" name="type" value="1"> <?= __( 'Private Message', WPGENT_DOMAIN ) ?>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" id="message_type2" name="type" value="2"> <?= __( 'Public Announcement', WPGENT_DOMAIN ) ?>
                      </label>
                    </div>
                  </div>
                  <div class="admin-item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="message_destination"><?= __( 'Destination', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                    <div class="col-md-10 col-sm-10 col-xs-12">
                      <p class="form-control static-value" id="fixed-sendto-id" readonly><?= __( 'All Users', WPGENT_DOMAIN ) ?></p>
                      <div id="sendto-id-finder" class="hide">
                        <div class="input-group">
                          <span class="input-group-btn">
                            <p class="btn btn-default active no-pointer" disabled="disabled"><i class="plt-search"></i></p>
                          </span>
                          <span class="input-group-btn input-group-separation">
                            <select class="form-control" id="find-id-type" style="width: 6em;">
                              <option value="to_user" selected="selected"><?= __( 'User', WPGENT_DOMAIN ) ?></option>
                              <option value="to_team"><?= __( 'Team', WPGENT_DOMAIN ) ?></option>
                            </select>
                          </span>
                          <input type="text" id="find-id" name="find_id" class="form-control" placeholder="<?= __( 'Please enter ID to find', WPGENT_DOMAIN ) ?>" value="" />
                          <span class="input-group-btn">
                            <button type="button" id="find-sendto-id" class="btn btn-default"><?= __( 'Find Recipient', WPGENT_DOMAIN ) ?></button>
                          </span>
                        </div>
                        <div class="selected-container">
                          <input id="selected-sendto-ids" class="form-control" />
                        </div>
                      </div><!-- /.sendto-id-finder -->
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                      <p class="help-block"><?= __( 'You should choose user or team as recipient. Or if you click the sender of received messages above, it is able to set as reply message.', WPGENT_DOMAIN ) ?></p>
                    </div>
                  </div>
                  <div class="admin-item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="message_subject"><?= __( 'Subject', WPGENT_DOMAIN ) ?></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" id="message_subject" name="subject" class="form-control" placeholder="<?= __( 'Please enter the subject', WPGENT_DOMAIN ) ?>" value="">
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                      <p class="help-block"><?= __( 'For message with subject specified, this subject is displayed in full text when the notification list is displayed.', WPGENT_DOMAIN ) ?></p>
                    </div>
                  </div>
                  <div class="admin-item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="message_content"><?= __( 'Message', WPGENT_DOMAIN ) ?> <span class="required"></span>
                      <p class="help-block remain-count"><?= __( 'Remains', WPGENT_DOMAIN ) ?>: <span class="count-strings" data-max-length="<?= $maxContentLength ?>"></span></p></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <textarea id="message_content" name="content" class="form-control" rows="5" placeholder="<?= __( 'Please enter the message', WPGENT_DOMAIN ) ?>" required="required"></textarea>
                      <i id="message_content_feedback" class="plt-warning form-control-feedback hide"></i>
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                      <p class="help-block"><?= __( 'If there is no subject, the message excerpt is displayed on the notification list.', WPGENT_DOMAIN ) ?></p>
                    </div>
                  </div>
                  <div class="admin-item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="expiration_date"><?= __( 'Expiration Date', WPGENT_DOMAIN ) ?></label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <input type="datetime" id="expiration_date" name="expiration_date" class="form-control" placeholder="0000-00-00 00:00:00" value="<?= esc_attr( '' ) ?>" disabled />
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                      <p class="help-block"><?= __( 'For messages with expiration date specified, they are become not displaying on the notification list after over that date.', WPGENT_DOMAIN ) ?></p>
                    </div>
                  </div>
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                      <button type="button" id="admin-send-message" class="btn btn-primary"><?= __( 'Send Message', WPGENT_DOMAIN ) ?></button>
                    </div>
                  </div>
<?php elseif ( WP_DEBUG ) : ?>
                  <h4><i class="plt-bubble-smiley blue"></i> <?= __( 'Message creation console for user', WPGENT_DOMAIN ) ?></h4>
                  <div class="ln_dotted ln_thin"></div>

                  <div class="item form-group">
                    <div class="input-group">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          <i class="plt-at-sign dark_gray"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">User 1</a></li>
                          <li><a href="#">User 2</a></li>
                          <li><a href="#">User 3</a></li>
                          <li><a href="#">Team 1</a></li>
                        </ul>
                      </span>
                      <textarea id="user_message_content" name="user_content" class="form-control" rows="1" placeholder="<?= __( 'Please enter the message', WPGENT_DOMAIN ) ?>"></textarea>
                      <span class="input-group-btn">
                        <button type="button" id="user-send-message" class="btn btn-primary"><i class="plt-upload3"></i></button>
                      </span>
                    </div>
                  </div>
<?php endif; ?>
                </form>
              </div>
            </div>
          </div><!-- /.flex-container -->
        </div>
        <!-- /.right_col -->
