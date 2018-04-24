/**
 * For Reset Password scripts (/resetpass/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#resetpassform"),
      wls              = window.localStorage,
      currentPermalink = 'resetpass';
  SUBMIT_BUTTONS       = [ 'submit' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  checkNotify();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Retrieve system notice, then show notify (:> システム通知を取得して表示する
   */
  function checkNotify() {
    var $notice = $('.account-notice');
    if ( $notice.children().length > 0 ) {
      var noticeType    = $notice.children('p').attr('class'),
          noticeTitle   = $notice.find('strong').text(),
          noticeMessage = '';
      $notice.children('p').find('strong').remove();
      noticeMessage = $notice.children('p')[0].innerHTML.trim().replace( /(<([^>]+)>)|\:/ig, '' );
      if ( 'error' === noticeType  ) {
        // noticeTitle = is_empty( noticeTitle ) ? localize_messages.error : noticeTitle;
        noticeTitle = localize_messages.error;
        notify( noticeTitle, noticeMessage, 'error', '', '' );
      } else {
        var opts = {
          title: is_empty( noticeTitle ) ? localize_messages.confirmation : noticeTitle,
          text: noticeMessage,
          textTrusted: true,
          addClass: '',
          type: 'info',
          icon: 'plt-info',
          hide: false,
          stack: {
            'dir1': 'down',
            'modal': true,
            'firstpos1': 25
          },
          modules: {
            Confirm: {
              confirm: true,
              buttons: [{
                text: localize_messages.dialog_yes,
                textTrusted: false,
                addClass: '',
                primary: false,
                promptTrigger: true,
                click: (notice, value) => {
                  notice.close();
                  notice.fire('pnotify.confirm', {notice, value});
                }
              }]
            },
            Buttons: {
              closer: false,
              sticker: false
            },
            History: {
              history: false
            }
          }
        };
        PNotify.alert( opts );
      }
      $notice.empty();
    }
  }
  
  
  // ----- WEBストレージ(ローカルストレージ)関連 ---------------------------------------------------------------
  
  
  
});